<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('page_templates')->onDelete('cascade'); // Relación con la plantilla
            $table->string('name')->unique(); // Nombre único de la página
            $table->json('data'); // Datos personalizados para la plantilla
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
