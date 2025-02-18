<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebToastSetting;

class WebToastSettingSeeder extends Seeder
{
    public function run()
    {
        WebToastSetting::firstOrCreate([], [
            'position' => 'top-right',  // 📌 Posición por defecto
            'timeout' => 5000,          // ⏳ Duración del toast en milisegundos
            'closeOnClick' => true,     // 🔘 Cierra al hacer clic
            'pauseOnHover' => true,     // ⏸ Pausa en hover
            'draggable' => true,        // 🖱 Permite arrastrar
        ]);
    }
}
