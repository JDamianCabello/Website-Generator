<?php

namespace App\Http\Controllers;

use App\Models\WebToastSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebToastSettingsController extends Controller
{
    public function show()
    {
        $settings = WebToastSetting::firstOrCreate([], [
            'position' => 'top-right',
            'timeout' => 5000,
            'closeOnClick' => true,
            'pauseOnHover' => true,
            'limit' => 5,
            'transition' => 'slide',
            'theme' => 'light'
        ]);

        // Usar el helper del modelo para obtener la configuración formateada
        $toastConfig = $settings->getToastConfig();
    
        return Inertia::render('Dashboard/Web/ShowToast', [
            'settings' => [
                'toast' => $toastConfig
            ],
            'availablePositions' => [
                ['value' => 'top-left', 'label' => 'Arriba Izquierda'],
                ['value' => 'top-center', 'label' => 'Arriba Centro'],
                ['value' => 'top-right', 'label' => 'Arriba Derecha'],
                ['value' => 'bottom-left', 'label' => 'Abajo Izquierda'],
                ['value' => 'bottom-center', 'label' => 'Abajo Centro'],
                ['value' => 'bottom-right', 'label' => 'Abajo Derecha'],
            ],
            'availableTransitions' => [
                ['value' => 'slide', 'label' => 'Deslizar'],
                ['value' => 'bounce', 'label' => 'Rebotar'],
                ['value' => 'zoom', 'label' => 'Zoom'],
                ['value' => 'flip', 'label' => 'Voltear'],
            ],
            'availableThemes' => [
                ['value' => 'light', 'label' => 'Claro'],
                ['value' => 'dark', 'label' => 'Oscuro'],
                ['value' => 'colored', 'label' => 'Colorido'],
            ]
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'position' => 'required|string|in:top-left,top-center,top-right,bottom-left,bottom-center,bottom-right',
            'timeout' => 'required|integer|min:1000',
            'closeOnClick' => 'required|boolean',
            'pauseOnHover' => 'required|boolean',
            'limit' => 'required|integer|min:1|max:10',
            'transition' => 'required|string|in:slide,bounce,zoom,flip',
            'theme' => 'required|string|in:light,dark,colored',
        ]);
    
        $settings = WebToastSetting::first();
        $settings->update($data);
    
        // Obtener la configuración actualizada usando el helper
        $updatedConfig = $settings->fresh()->getToastConfig();
    
        return redirect()->back()->with([
            'success' => 'Configuración actualizada correctamente',
            'settings' => ['toast' => $updatedConfig]
        ]);
    }

    public function restore()
    {
        $settings = WebToastSetting::first();
        $defaultConfig = WebToastSetting::getDefaultConfig();
        
        $settings->update($defaultConfig);

        return redirect()->back()->with([
            'success' => 'Configuración restaurada a valores predeterminados',
            'settings' => ['toast' => $defaultConfig]
        ]);
    }
}