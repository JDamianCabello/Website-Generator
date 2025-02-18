import { toast } from 'vue3-toastify';
import { usePage } from '@inertiajs/vue3';

export const useToast = () => {
    const getToastConfig = (customConfig = {}) => {
        const settings = usePage().props.settings?.toast;
        return {
            position: settings?.position || 'top-right',
            autoClose: Number(settings?.autoClose || 5000),
            closeOnClick: settings?.closeOnClick ?? true,
            pauseOnHover: settings?.pauseOnHover ?? true,
            draggable: settings?.draggable ?? false,
            limit: settings?.limit ?? 5,
            transition: settings?.transition || 'slide',
            theme: settings?.theme || 'light',
            ...customConfig
        };
    };

    const showToast = (message, type = 'info', customConfig = {}) => {
        const config = getToastConfig(customConfig);
        
        switch (type) {
            case 'success':
                return toast.success(message, config);
            case 'error':
                return toast.error(message, config);
            case 'warning':
                return toast.warning(message, config);
            case 'loading':
                return toast.loading(message, config);
            default:
                return toast.info(message, config);
        }
    };

    const showLoadingToast = (message, callback) => {
        const id = toast.loading(message, getToastConfig());
        if (callback) {
            callback(id, (newMessage, type = 'success') => {
                toast.update(id, {
                    render: newMessage,
                    type,
                    isLoading: false,
                    ...getToastConfig()
                });
                setTimeout(() => toast.done(id), 1000);
            });
        }
        return id;
    };

    return {
        success: (message, config = {}) => showToast(message, 'success', config),
        error: (message, config = {}) => showToast(message, 'error', config),
        warning: (message, config = {}) => showToast(message, 'warning', config),
        info: (message, config = {}) => showToast(message, 'info', config),
        loading: showLoadingToast,
        // Para casos especiales donde necesitas una notificación sin autoClose
        permanent: (message, type = 'info') => showToast(message, type, { autoClose: false })
    };
};