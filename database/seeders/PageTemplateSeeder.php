<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageTemplate;

class PageTemplateSeeder extends Seeder
{
    public function run()
    {
        PageTemplate::create([
            'name' => 'Portafolio Personal',
            'description' => 'Plantilla de portafolio moderno',
            'view_path' => 'templates.personal-portfolio',
            'fields' => [
                'pageTitle' => [
                    'type' => 'text',
                    'label' => 'Título de la Página',
                    'required' => true,
                ],
                'name' => [
                    'type' => 'text',
                    'label' => 'Nombre',
                    'required' => true,
                ],
                'professionalTitle' => [
                    'type' => 'text',
                    'label' => 'Título Profesional',
                    'required' => true,
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Descripción',
                    'required' => true,
                ],
                'technologies' => [
                    'type' => 'multiselect',
                    'label' => 'Tecnologías Principales',
                    'required' => true,
                    'options' => ['Laravel', 'Vue.js', 'Tailwind'],
                ],
                'showContact' => [
                    'type' => 'boolean',
                    'label' => 'Mostrar sección de contacto',
                    'required' => false,
                ],
                'email' => [
                    'type' => 'email',
                    'label' => 'Email',
                    'required' => false,
                ],
                'linkedinUrl' => [
                    'type' => 'url',
                    'label' => 'Perfil de LinkedIn',
                    'required' => false,
                ],
            ],
            'default_values' => [
                'pageTitle' => 'JD Cabello - Desarrollador Web',
                'name' => 'jdamiancabello',
                'professionalTitle' => 'Desarrollador Web Full Stack',
                'description' => 'Creando soluciones web innovadoras',
                'technologies' => ['Laravel', 'Vue.js', 'Tailwind'],
                'showContact' => true,
                'email' => 'contacto@example.com',
                'linkedinUrl' => 'https://linkedin.com/in/example'
            ],
            'type' => 'portfolio'
        ]);
        
        
    }
    
}