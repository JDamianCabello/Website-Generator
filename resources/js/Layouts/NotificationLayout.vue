<template>
  <div>
    <slot></slot>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { toast, updateGlobalOptions } from 'vue3-toastify';
import { watch, onMounted } from 'vue';

// Obtener la configuración del toast
const toastConfig = usePage().props.settings.toast;

// Configurar el toast cuando cambie la configuración
watch(() => usePage().props.settings.toast, (newConfig) => {
  if (newConfig) {
    toast.clearAll(); // Limpiar toasts existentes
    // Asegurarnos de que autoClose sea un número
    const config = {
      ...newConfig,
      autoClose: Number(newConfig.autoClose)
    };
    updateGlobalOptions(config);
  }
}, { deep: true });

// Configurar el toast al montar el componente
onMounted(() => {
  if (toastConfig) {
    updateGlobalOptions({
      ...toastConfig,
      autoClose: Number(toastConfig.autoClose)
    });
  }
});

// Métodos para mostrar notificaciones
const showSuccess = (message, options = {}) => {
  const config = {
    ...toastConfig,
    autoClose: Number(toastConfig.autoClose),
    ...options
  };
  toast.success(message, config);
};

const showError = (message, options = {}) => {
  const config = {
    ...toastConfig,
    autoClose: Number(toastConfig.autoClose),
    ...options
  };
  toast.error(message, config);
};

// Añadimos un método para mostrar cualquier tipo de notificación
const showNotification = (message, type = 'info', options = {}) => {
  const config = {
    ...toastConfig,
    autoClose: Number(toastConfig.autoClose),
    ...options
  };
  
  switch(type) {
    case 'success':
      toast.success(message, config);
      break;
    case 'error':
      toast.error(message, config);
      break;
    case 'warning':
      toast.warning(message, config);
      break;
    default:
      toast.info(message, config);
  }
};

// Exponer los métodos
defineExpose({
  showSuccess,
  showError,
  showNotification
});
</script>