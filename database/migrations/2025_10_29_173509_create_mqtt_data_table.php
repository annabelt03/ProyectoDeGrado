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
   public function up()
{
    Schema::create('mqtt_data', function (Blueprint $table) {
        $table->id();
        $table->string('topic');      // Tópico MQTT, por ejemplo "sensor/temp"
        $table->string('value');      // Valor recibido (puede ser texto o número)
        $table->timestamps();         // created_at y updated_at automáticos
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mqtt_data');
    }
};
