<template>
  <DashboardLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          Crear Nuevo Rol
        </h1>
        <p class="mt-1 text-sm text-gray-500">Completa la información para crear un nuevo rol en el sistema.</p>
      </div>

      <!-- Formulario -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form @submit.prevent="submit" class="divide-y divide-gray-200">
          <!-- Información del Rol -->
          <div class="p-6 space-y-6">
            <h2 class="text-lg font-medium text-gray-900">Detalles del Rol</h2>
            <div class="grid grid-cols-1 gap-6">
              <!-- Nombre del Rol -->
              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Nombre del Rol
                  <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  v-model="form.name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.name }"
                  placeholder="Ej: Administrador, Editor, Consultor"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
              </div>
            </div>
          </div>

          <!-- Permisos -->
          <div class="p-6 space-y-6">
            <h2 class="text-lg font-medium text-gray-900">Permisos Asignados</h2>
            
            <!-- Barra de búsqueda de permisos -->
            <div class="mb-4">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  type="text"
                  v-model="permissionSearch"
                  placeholder="Buscar permisos..."
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
              </div>
            </div>

            <div v-if="filteredPermissions.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div 
                v-for="permission in filteredPermissions" 
                :key="permission.id" 
                class="flex items-center"
              >
                <input
                  type="checkbox"
                  :id="'permission-' + permission.id"
                  :value="permission.name"
                  v-model="form.permissions"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label 
                  :for="'permission-' + permission.id" 
                  class="ml-2 block text-sm text-gray-900"
                >
                  {{ permission.name }}
                </label>
              </div>
            </div>
            <p v-else class="text-gray-500 text-center">
              No se encontraron permisos que coincidan con la búsqueda
            </p>
            
            <p v-if="errors.permissions" class="mt-1 text-sm text-red-600">{{ errors.permissions }}</p>
            <p class="mt-2 text-sm text-gray-500">
              Selecciona los permisos que tendrá este rol
            </p>
          </div>

          <!-- Botones de Acción -->
          <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
            <Link
              :href="route('dashboard.roles')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Cancelar
            </Link>
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
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
              {{ form.processing ? 'Guardando...' : 'Crear Rol' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useToast } from '@/Utils/toast';
import { ref, computed } from 'vue';

// Define props with default values
const props = defineProps({
  availablePermissions: {
    type: Array,
    default: () => [] // Provide an empty array as default
  },
  errors: {
    type: Object,
    default: () => ({})
  }
});

const toast = useToast();

// Search input for permissions
const permissionSearch = ref('');

// Compute filtered permissions based on search input
const filteredPermissions = computed(() => {
  // If no search term, return all permissions
  if (!permissionSearch.value.trim()) {
    return props.availablePermissions;
  }
  
  // Filter permissions when there's a search term
  return props.availablePermissions.filter(permission =>
    permission.name.toLowerCase().includes(permissionSearch.value.toLowerCase())
  );
});

const form = useForm({
  name: '',
  permissions: [],
});

const submit = () => {
  form.post(route('dashboard.roles.store'), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Rol creado correctamente');
      form.reset(); // Reset the form after successful submission
      permissionSearch.value = ''; // Clear the search input
    },
    onError: () => {
      toast.error('Error al crear el rol');
    }
  });
};
</script>