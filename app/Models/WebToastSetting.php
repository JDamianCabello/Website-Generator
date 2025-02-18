<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebToastSetting extends Model
{
    protected $fillable = [
        'position',
        'timeout',
        'closeOnClick',
        'pauseOnHover',
        'limit',
        'transition',
        'theme'
    ];

    protected $casts = [
        'closeOnClick' => 'boolean',
        'pauseOnHover' => 'boolean',
        'timeout' => 'integer',
        'limit' => 'integer'
    ];

    // Helper para obtener la configuración formateada para el toast
    public function getToastConfig(): array
    {
        return [
            'position' => $this->position,
            'autoClose' => $this->timeout,
            'closeOnClick' => $this->closeOnClick,
            'pauseOnHover' => $this->pauseOnHover,
            'limit' => $this->limit,
            'transition' => $this->transition,
            'theme' => $this->theme
        ];
    }

    // Helper para obtener configuración por defecto
    public static function getDefaultConfig(): array
    {
        return [
            'position' => 'top-right',
            'autoClose' => 5000,
            'closeOnClick' => true,
            'pauseOnHover' => true,
            'limit' => 5,
            'transition' => 'slide',
            'theme' => 'light'
        ];
    }
}