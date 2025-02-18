<template>
  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header con búsqueda -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Gestión de Plantillas</h1>
          <p class="mt-1 text-sm text-gray-500">Administra las plantillas disponibles para tu sitio</p>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Barra de búsqueda -->
          <div class="relative">
            <input 
              type="text" 
              v-model="search"
              placeholder="Buscar plantilla..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <Link 
            href="/dashboard/templates/create" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nueva Plantilla
          </Link>
        </div>
      </div>

      <!-- Grid de Plantillas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="template in filteredTemplates" 
          :key="template.id" 
          class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow"
        >
          <!-- Imagen de la plantilla -->
          <div class="aspect-video w-full overflow-hidden">
            <img 
              :src="template.preview_image || '/storage/templates/default.png'" 
              :alt="template.name" 
              class="w-full h-full object-cover"
            >
          </div>

          <!-- Contenido -->
          <div class="p-4">
            <div class="flex justify-between items-start">
              <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ template.name }}</h2>
                <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ template.description }}</p>
              </div>
              <span 
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="template.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
              >
                {{ template.is_active ? 'Activa' : 'Inactiva' }}
              </span>
            </div>

            <div class="mt-4 text-sm text-gray-500">
              Última actualización: {{ formatDate(template.updated_at) }}
            </div>

            <!-- Acciones -->
            <div class="mt-4 flex justify-end space-x-2">
              <Link 
                :href="`/dashboard/templates/${template.id}`" 
                class="text-blue-600 hover:text-blue-700 transition-colors">
                <span class="sr-only">Ver</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </Link>
              <Link 
                :href="`/dashboard/templates/${template.id}/edit`" 
                class="text-yellow-600 hover:text-yellow-700 transition-colors">
                <span class="sr-only">Editar</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </Link>
              <button 
                @click="confirmDelete(template)"
                class="text-red-600 hover:text-red-700 transition-colors">
                <span class="sr-only">Eliminar</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useToast } from '@/Utils/toast';
import { ref, computed } from 'vue';

const toast = useToast();
const search = ref('');

const props = defineProps({
  templates: {
    type: Array,
    required: true
  }
});

// Filtrar templates basado en la búsqueda
const filteredTemplates = computed(() => {
  if (!search.value) return props.templates;
  
  const searchTerm = search.value.toLowerCase();
  return props.templates.filter(template => 
    template.name.toLowerCase().includes(searchTerm) ||
    template.description?.toLowerCase().includes(searchTerm)
  );
});

// Formatear fecha
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const confirmDelete = (template) => {
  if (window.confirm(`¿Estás seguro de que deseas eliminar la plantilla "${template.name}"?`)) {
    router.delete(`/dashboard/templates/${template.id}`, {
      onSuccess: () => {
        toast.success('Plantilla eliminada correctamente');
      },
      onError: () => {
        toast.error('Error al eliminar la plantilla');
      }
    });
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>