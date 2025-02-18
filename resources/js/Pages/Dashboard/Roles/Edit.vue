<template>
  <DashboardLayout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header con diseño mejorado -->
      <div class="mb-8 border-b border-gray-200 pb-6">
        <div class="flex justify-between items-start">
          <div>
            <h1 class="text-3xl font-bold text-gray-900" v-if="role">
              {{ role.name }}
            </h1>
            <p class="mt-2 text-sm text-gray-600">
              Gestiona los permisos y accesos asociados a este rol del sistema
            </p>
          </div>
          <Link 
            href="/dashboard/roles" 
            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
          >
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a roles
          </Link>
        </div>
      </div>

      <!-- Contenido Principal -->
      <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg">
        <form @submit.prevent="submit" class="divide-y divide-gray-200" v-if="role && permissions">
          <!-- Sección de información del rol -->
          <div class="p-6">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Información del Rol</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
              Este es un rol del sistema con permisos específicos asignados.
            </p>
            
            <div class="mt-6">
              <label class="text-sm font-medium text-gray-900">Nombre del Rol</label>
              <input 
                v-model="form.name" 
                type="text" 
                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3
                       text-gray-900 ring-1 ring-inset ring-gray-300 
                       bg-gray-50/50 read-only:text-gray-500
                       sm:text-sm sm:leading-6" 
                disabled 
              />
            </div>
          </div>

          <!-- Sección de permisos -->
          <div class="p-6">
            <div class="mb-6">
              <h2 class="text-base font-semibold leading-7 text-gray-900">Permisos del Rol</h2>
              <p class="mt-1 text-sm leading-6 text-gray-600">
                Selecciona los permisos que deseas asignar a este rol.
              </p>
            </div>

            <!-- Buscador de permisos mejorado -->
            <div class="mb-6">
              <div class="relative">
                <input
                  type="text"
                  v-model="permissionSearch"
                  placeholder="Buscar permiso..."
                  class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3
                         text-gray-900 ring-1 ring-inset ring-gray-300
                         placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600
                         sm:text-sm sm:leading-6"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Lista de permisos mejorada -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <div class="max-h-[460px] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1 p-1">
                  <div 
                    v-for="permission in filteredPermissions" 
                    :key="permission.id" 
                    class="relative flex items-center p-3 rounded-md hover:bg-gray-50"
                  >
                    <div class="flex items-center h-6">
                      <input 
                        type="checkbox" 
                        v-model="form.permissions" 
                        :value="permission.name" 
                        :id="permission.id"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 
                               focus:ring-blue-600 focus:ring-offset-0"
                      />
                    </div>
                    <label 
                      :for="permission.id" 
                      class="ml-3 flex flex-col cursor-pointer text-sm"
                    >
                      <span class="font-medium text-gray-900">{{ permission.name }}</span>
                      <span class="text-gray-500 text-xs">Permiso del sistema</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer con botones de acción -->
          <div class="px-6 py-4 flex items-center justify-end gap-x-4 bg-gray-50/50">
            <Link 
              href="/dashboard/roles" 
              class="inline-flex items-center px-4 py-2 text-sm font-semibold 
                     text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 
                     focus:ring-blue-600 focus:ring-offset-2"
            >
              Cancelar
            </Link>
            <button 
              type="submit" 
              class="inline-flex items-center px-4 py-2 text-sm font-semibold
                     text-white bg-blue-600 hover:bg-blue-700 rounded-md
                     focus:outline-none focus:ring-2 focus:ring-blue-600 
                     focus:ring-offset-2 disabled:opacity-50 
                     disabled:cursor-not-allowed transition-colors"
              :disabled="form.processing"
            >
              <svg 
                v-if="form.processing" 
                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path 
                  class="opacity-75" 
                  fill="currentColor" 
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                />
              </svg>
              {{ form.processing ? 'Guardando cambios...' : 'Guardar cambios' }}
            </button>
          </div>
        </form>

        <!-- Estado de carga mejorado -->
        <div v-else class="p-8">
          <div class="text-center">
            <div class="animate-pulse inline-block">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path 
                  class="opacity-75" 
                  fill="currentColor" 
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                />
              </svg>
            </div>
            <h3 class="mt-4 text-sm font-medium text-gray-900">Cargando información del rol</h3>
            <p class="mt-1 text-sm text-gray-500">Por favor, espera un momento...</p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useToast } from '@/Utils/toast';

const props = defineProps({
  role: {
    type: Object,
    required: true
  },
  permissions: {
    type: Array,
    required: true
  }
});

const toast = useToast();
const permissionSearch = ref('');

const form = useForm({
  name: props.role?.name || '',
  permissions: props.role?.permissions?.map(p => p.name) || [],
});

const filteredPermissions = computed(() => {
  if (!props.permissions) return [];
  return props.permissions.filter(permission =>
    permission.name.toLowerCase().includes(permissionSearch.value.toLowerCase())
  );
});

const submit = () => {
  if (!props.role?.id) return;
  
  form.post(`/dashboard/roles/${props.role.id}/update`, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Los permisos del rol "${props.role.name}" han sido actualizados correctamente.`);
    },
    onError: () => {
      toast.error('Ha ocurrido un error al actualizar los permisos. Por favor, inténtalo de nuevo.');
    }
  });
};
</script>