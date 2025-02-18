<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('web_toast_settings', function (Blueprint $table) {
            $table->id();
            $table->string('position')->default('top-right'); // Posición del toast
            $table->integer('timeout')->default(5000); // Duración en milisegundos
            $table->boolean('closeOnClick')->default(true); // Cerrar al hacer clic
            $table->boolean('pauseOnHover')->default(true); // Pausar en hover
            $table->integer('limit')->default(5); // Máximo de notificaciones
            $table->string('transition')->default('slide'); // Tipo de transición
            $table->string('theme')->default('light'); // Tema del toast
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('web_toast_settings');
    }
};