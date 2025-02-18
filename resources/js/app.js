import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Vue3Toastify, { updateGlobalOptions, toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Configuración por defecto
        const defaultToastConfig = {
            position: 'top-right',
            autoClose: 5000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            theme: 'light',
            limit: 5,
            transition: 'slide',
            hideProgressBar: false,
            closeButton: true,
            rtl: false
        };

        // Obtener configuración del toast
        const toastSettings = props.initialPage.props.settings?.toast;
        
        if (toastSettings) {
            // Asegurarnos que la configuración sea la correcta y aplicarla globalmente
            const toastConfig = {
                ...defaultToastConfig,
                position: toastSettings.position,
                autoClose: Number(toastSettings.autoClose),
                closeOnClick: toastSettings.closeOnClick,
                pauseOnHover: toastSettings.pauseOnHover,
                draggable: toastSettings.draggable,
                theme: toastSettings.theme || 'light',
                limit: Number(toastSettings.limit),
                transition: toastSettings.transition,
            };

            // Instalar Vue3Toastify con la configuración
            app.use(Vue3Toastify, toastConfig);

            // Actualizar las opciones globales
            updateGlobalOptions(toastConfig);

            // Hacer el toast disponible globalmente
            app.config.globalProperties.$toast = toast;

            // Definir alias globales para los tipos de toast
            app.config.globalProperties.$notify = {
                success: (message, options = {}) => 
                    toast.success(message, { ...toastConfig, ...options }),
                error: (message, options = {}) => 
                    toast.error(message, { ...toastConfig, ...options }),
                info: (message, options = {}) => 
                    toast.info(message, { ...toastConfig, ...options }),
                warning: (message, options = {}) => 
                    toast.warning(message, { ...toastConfig, ...options }),
                loading: (message, options = {}) => 
                    toast.loading(message, { ...toastConfig, ...options }),
                update: (toastId, options) => 
                    toast.update(toastId, { ...toastConfig, ...options }),
                dismiss: toast.dismiss,
                clearAll: toast.clearAll,
                isActive: toast.isActive
            };
        } else {
            // Si no hay configuración, usar valores por defecto
            app.use(Vue3Toastify, defaultToastConfig);
            updateGlobalOptions(defaultToastConfig);
            app.config.globalProperties.$toast = toast;
        }

        app.use(plugin);
        app.use(ZiggyVue);

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});