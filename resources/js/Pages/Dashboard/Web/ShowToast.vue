<template>
  <DashboardLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header con botón de restaurar -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Configuración de Notificaciones</h1>
          <p class="text-gray-600 mt-2">Personaliza cómo se muestran las notificaciones en tu sitio web.</p>
        </div>
        <button
          type="button"
          @click="restoreDefaults"
          class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
        >
          <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Restaurar Valores Predeterminados
        </button>
      </div>

      <!-- Formulario Principal -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form @submit.prevent="submit" class="divide-y divide-gray-200">
          <!-- Configuración Principal -->
          <div class="p-6 space-y-6">
            <!-- Posición -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Posición de las Notificaciones
              </label>
              <select 
                v-model="form.position" 
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md transition-colors"
              >
                <option 
                  v-for="position in availablePositions" 
                  :key="position.value" 
                  :value="position.value"
                >
                  🔹 {{ position.label }}
                </option>
              </select>
              <p class="mt-2 text-sm text-gray-500">
                Selecciona dónde aparecerán las notificaciones en la pantalla
              </p>
            </div>

            <!-- Tiempo de Duración -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Tiempo de Duración
              </label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <input 
                  type="number" 
                  v-model="form.timeout" 
                  min="1000"
                  step="500"
                  class="block w-full pl-3 pr-12 py-2 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">ms</span>
                </div>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                1000ms = 1 segundo. Mínimo recomendado: 2000ms
              </p>
            </div>

            <!-- Límite de Notificaciones -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Límite de Notificaciones
              </label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <input 
                  type="number" 
                  v-model="form.limit" 
                  min="1"
                  max="10"
                  class="block w-full pl-3 pr-12 py-2 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
                />
              </div>
              <p class="mt-2 text-sm text-gray-500">
                Número máximo de notificaciones mostradas simultáneamente (1-10)
              </p>
            </div>

            <!-- Transición -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Animación de Transición
              </label>
              <select 
                v-model="form.transition" 
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md transition-colors"
              >
                <option 
                  v-for="transition in availableTransitions" 
                  :key="transition.value" 
                  :value="transition.value"
                >
                  🔸 {{ transition.label }}
                </option>
              </select>
              <p class="mt-2 text-sm text-gray-500">
                Selecciona el estilo de animación para las notificaciones
              </p>
            </div>

            <!-- Tema -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Tema de Notificaciones
              </label>
              <select 
                v-model="form.theme" 
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md transition-colors"
              >
                <option 
                  v-for="theme in availableThemes" 
                  :key="theme.value" 
                  :value="theme.value"
                >
                  🎨 {{ theme.label }}
                </option>
              </select>
              <p class="mt-2 text-sm text-gray-500">
                Elige el estilo visual de las notificaciones
              </p>
            </div>
          </div>

          <!-- Opciones Adicionales -->
          <div class="p-6 space-y-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Opciones Adicionales</h3>
            
            <div class="space-y-4">
              <!-- Cerrar al hacer clic -->
              <div class="relative flex items-start">
                <div class="flex items-center h-5">
                  <input 
                    type="checkbox" 
                    v-model="form.closeOnClick"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors"
                  />
                </div>
                <div class="ml-3">
                  <label class="font-medium text-gray-700">Cerrar al hacer clic</label>
                  <p class="text-sm text-gray-500">La notificación se cerrará cuando el usuario haga clic en ella</p>
                </div>
              </div>

              <!-- Pausar al pasar el cursor -->
              <div class="relative flex items-start">
                <div class="flex items-center h-5">
                  <input 
                    type="checkbox" 
                    v-model="form.pauseOnHover"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors"
                  />
                </div>
                <div class="ml-3">
                  <label class="font-medium text-gray-700">Pausar al pasar el cursor</label>
                  <p class="text-sm text-gray-500">La cuenta regresiva se pausará cuando el cursor esté sobre la notificación</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de Acción -->
          <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
            <button
              type="button"
              @click="testNotification"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors disabled:opacity-50"
              :disabled="form.processing"
            >
              <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              Probar Notificación
            </button>

            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50"
              :disabled="form.processing"
            >
              <svg 
                v-if="form.processing" 
                class="animate-spin -ml-1 mr-2 h-4 w-4" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
              </svg>
              {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { toast, updateGlobalOptions } from 'vue3-toastify';
import { computed, onMounted } from 'vue';

const props = defineProps({
  settings: {
    type: Object,
    required: true
  },
  availablePositions: {
    type: Array,
    required: true
  },
  availableTransitions: {
    type: Array,
    required: true
  },
  availableThemes: {
    type: Array,
    required: true
  }
});

// Crear el formulario con los valores actuales
const form = useForm({
  position: props.settings.toast.position,
  timeout: props.settings.toast.autoClose,
  closeOnClick: props.settings.toast.closeOnClick,
  pauseOnHover: props.settings.toast.pauseOnHover,
  limit: props.settings.toast.limit,
  transition: props.settings.toast.transition,
  theme: props.settings.toast.theme,
});

// Configuración actual del toast
const currentToastConfig = computed(() => ({
  position: form.position,
  autoClose: Number(form.timeout),
  closeOnClick: form.closeOnClick,
  pauseOnHover: form.pauseOnHover,
  limit: Number(form.limit),
  transition: form.transition,
  theme: form.theme,
}));

// Función para actualizar la configuración global
const updateToastConfig = (config) => {
  const updatedConfig = {
    ...config,
    autoClose: Number(config.autoClose),
    limit: Number(config.limit)
  };
  
  toast.clearAll();
  updateGlobalOptions(updatedConfig);
};

// Restaurar valores predeterminados
const restoreDefaults = () => {
  if (window.confirm('¿Estás seguro de que deseas restaurar la configuración predeterminada?')) {
    form.post(route('dashboard.web.toast.restore'), {
      preserveScroll: true,
      onSuccess: (page) => {
        // Actualizar el formulario con los datos devueltos
        const newSettings = page.props.settings.toast;
        
        // Actualizar cada campo del formulario
        form.position = newSettings.position;
        form.timeout = newSettings.autoClose;
        form.closeOnClick = newSettings.closeOnClick;
        form.pauseOnHover = newSettings.pauseOnHover;
        form.limit = newSettings.limit;
        form.transition = newSettings.transition;
        form.theme = newSettings.theme;

        // Actualizar la configuración global de toast
        updateToastConfig(newSettings);
        
        setTimeout(() => {
          toast.success('Configuración restaurada a valores predeterminados', newSettings);
        }, 100);
      },
    });
  }
};

// Enviar la configuración al backend
const submit = () => {
  form.post('/dashboard/web/toast/update', {
    preserveScroll: true,
    onSuccess: () => {
      const newConfig = currentToastConfig.value;
      updateToastConfig(newConfig);
      
      setTimeout(() => {
        toast.success('Configuración guardada correctamente', newConfig);
      }, 100);
    },
  });
};

// Función para lanzar una notificación de prueba
const testNotification = () => {
  const config = currentToastConfig.value;
  toast.info('Esta es una notificación de prueba 🎉', config);
};

onMounted(() => {
  updateToastConfig(currentToastConfig.value);
});
</script>