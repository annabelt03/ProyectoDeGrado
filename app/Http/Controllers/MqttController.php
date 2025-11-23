<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroPunto;

class MqttController extends Controller
{
    public function store(Request $request)
    {
      $topic = $request->input('topic');
        $value = $request->input('value');

        // 2. (Paso 3) Guardar en base de datos los datos recibidos...
        // 3. Devolver una respuesta al cliente Node.js
        return response()->json(['message' => 'Datos recibidos en Laravel'], 200);

        // Guardamos en base de datos si aplica
        RegistroPunto::create([
            'msg_id' => uniqid('mqtt_'),
            'numeroRFID' => null, // podrías capturar más si lo envías
            'peso_kg' => is_numeric($validated['value']) ? floatval($validated['value']) : 0.0,
            'puntos_asignados' => 0.0, // podrías llamar a tu servicio de cálculo aquí
        ]);

        return response()->json(['message' => 'Datos guardados con éxito'], 201);

        // ... dentro de MqttController@store, después de obtener $topic y $value:
$entry = new MqttData();
$entry->topic = $topic;
$entry->value = $value;
$entry->save();  // Guarda en la base de datos:contentReference[oaicite:4]{index=4}

    }
}
