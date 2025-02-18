<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('view_path'); // Ruta de la vista
            $table->json('fields')->nullable();
            $table->json('default_values')->nullable();
            $table->string('preview_image')->nullable();
            $table->enum('type', ['landing', 'portfolio', 'blog', 'ecommerce', 'corporate']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_templates');
    }
};
