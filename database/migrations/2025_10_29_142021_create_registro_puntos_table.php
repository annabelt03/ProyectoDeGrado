<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_puntos', function (Blueprint $table) {
            $table->id();

            // Idempotencia: cada mensaje del dispositivo debe ser único
            $table->uuid('msg_id')->unique();

            // Usuario opcional (por ahora), FK hacia la tabla usuarios
            $table->foreignId('usuario_id')
                  ->nullable() // ✅ opcional ahora, luego lo podemos volver obligatorio
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            // RFID opcional (para cuando lo uses en el futuro)
            $table->string('numeroRFID', 8)->nullable()->index();

            // Datos del evento
            $table->unsignedInteger('peso_gramos');        // guardamos en gramos (ABS del sensor)
            $table->decimal('puntos_asignados', 8, 2);     // permite decimales (ej: 0.5, 0.58, 10.00)

            // Timestamp opcional que viene del dispositivo
            $table->timestamp('leido_en')->nullable();

            $table->timestamps();

            // Índice útil para reportes por usuario y fecha
            $table->index(['usuario_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_puntos');
    }
};
