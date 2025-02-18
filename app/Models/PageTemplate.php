<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'view_path',
        'fields', 
        'default_values', 
        'preview_image', 
        'type', 
        'is_active'
    ];

    protected $casts = [
        'fields'         => 'array',
        'default_values' => 'array',
        'is_active'      => 'boolean',
        'current_values' => 'array' 
    ];

    // Método render para generar el HTML con los valores por defecto
    public $current_values; // propiedad pública para almacenar los valores actuales

    // Método render para generar el HTML con los valores por defecto
    public function render(array $customValues = [], bool $is_preview = true)
    {
        $fields = $this->fields ?? [];
        $processedValues = [];
    
        // Procesar cada campo según su tipo
        foreach ($fields as $key => $field) {
            if (isset($customValues[$key])) {
                switch ($field['type']) {
                    case 'boolean':
                        $processedValues[$key] = filter_var($customValues[$key], FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'multiselect':
                        $processedValues[$key] = is_string($customValues[$key]) 
                            ? array_filter(array_map('trim', explode(',', $customValues[$key])))
                            : (array) $customValues[$key];
                        break;
                    default:
                        $processedValues[$key] = $customValues[$key];
                }
            }
        }
    
        // Fusionar con valores por defecto
        $values = array_merge($this->default_values ?? [], $processedValues);
        
        // Guardar los valores actuales procesados
        $this->current_values = $values;
    
        // Renderizar la vista
        $html = view($this->view_path, $values)->render();
    
        if ($is_preview) {
            $cdn = '<script src="https://cdn.tailwindcss.com"></script>';
            $html = str_replace('</head>', $cdn . '</head>', $html);
        }
    
        return $html;
    }
    
    
    
}
