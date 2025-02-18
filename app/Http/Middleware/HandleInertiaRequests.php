<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\WebToastSetting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        // Obtener configuración de notificaciones
        $toastConfig = WebToastSetting::first()?->getToastConfig() ?? \App\Models\WebToastSetting::getDefaultConfig();
    
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'permissions' => $request->user()?->getAllPermissions()->pluck('name') ?? [],
                ] : null,
            ],
            'settings' => [
                'toast' => $toastConfig
            ],
        ]);
    }
}
