<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\Facades\MQTT;
use App\Services\PuntosService;
use App\Models\RegistroPunto;

class MqttConsumeEcorecicla extends Command
{
    protected $signature = 'mqtt:ecorecicla-consume';
    protected $description = 'Suscribe al tópico MQTT de EcoRecicla y guarda registros de puntos.';

    public function handle(PuntosService $puntosService): int
    {
        $topic = config('mqtt_topic.ecorecicla', 'ecorecicla/lecturas');

        $client = MQTT::connection(); // usa la conexión "default" de config/mqtt.php

        $this->info("MQTT conectado. Suscrito a: {$topic}");

        $client->subscribe($topic, function (string $topic, string $message) use ($puntosService) {
            // 1) Parseo JSON
            try {
                $payload = json_decode($message, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $e) {
                Log::warning('[MQTT] JSON inválido', ['raw' => $message, 'err' => $e->getMessage()]);
                return;
            }

            // 2) Normalización peso (acepta peso_kg o peso_g)
            $msgId      = $payload['msg_id']     ?? null;
            $numeroRFID = $payload['numeroRFID'] ?? null; // por ahora puede ser null
            $pesoKg     = null;

            if (isset($payload['peso_kg'])) {
                $pesoKg = (float) $payload['peso_kg'];
            } elseif (isset($payload['peso_g'])) {
                $pesoKg = round(((float) $payload['peso_g']) / 1000, 3);
            }

            if ($pesoKg === null) {
                Log::warning('[MQTT] Mensaje sin campo de peso', ['payload' => $payload]);
                return;
            }

            // 3) Calcular puntos (tu regla + cap en 10)
            $puntos = $puntosService->calcular($pesoKg);

            // 4) Guardar con idempotencia opcional por msg_id
            try {
                DB::transaction(function () use ($msgId, $numeroRFID, $pesoKg, $puntos, $payload) {
                    if (!empty($msgId) && RegistroPunto::where('msg_id', $msgId)->exists()) {
                        return; // ya procesado
                    }

                    RegistroPunto::create([
                        'msg_id'           => $msgId,
                        'numeroRFID'       => $numeroRFID,   // null permitido
                        'peso_kg'          => $pesoKg,       // se guarda tal cual (incluye negativos)
                        'puntos_asignados' => $puntos,       // si peso<0 => 0; si >0 => regla; cap 10
                        'meta'             => [
                            'origen' => 'mqtt',
                            'raw'    => $payload, // trazabilidad
                        ],
                    ]);
                });
            } catch (\Throwable $e) {
                Log::error('[MQTT] Error guardando', ['err' => $e->getMessage(), 'payload' => $payload]);
                return;
            }

            Log::info('[MQTT] Registro guardado', ['msg_id' => $msgId, 'peso_kg' => $pesoKg, 'puntos' => $puntos]);
        }, 1); // QoS 1

        // 5) Loop (bloqueante)
        $client->loop(true);

        return self::SUCCESS;
    }
}
