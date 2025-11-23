<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('primerApellido');
            $table->string('segundoApellido')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->enum('genero', ['m','f'])->nullable();
            $table->string('numeroRFID',8)->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['administrador','estudiante'])->default('estudiante');
            $table->integer('puntos')->default(0);
            $table->enum('estado', ['activo','inactivo','suspendido'])->default('activo');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};


