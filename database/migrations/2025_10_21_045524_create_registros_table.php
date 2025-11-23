<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->integer('puntos_totales');
            $table->string('estado')->default('canjeado'); // canjeado, entregado, cancelado
            $table->timestamp('fecha_canjeo')->useCurrent();
            $table->timestamps();
            
            // Índices para mejor performance
            $table->index('usuario_id');
            $table->index('producto_id');
            $table->index('fecha_canjeo');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
