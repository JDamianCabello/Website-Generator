<template>
    <DashboardLayout>
      <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Editar Página</h1>
  
        <form @submit.prevent="submit">
          <!-- Nombre -->
          <div class="mb-4">
            <label class="block font-semibold">Nombre</label>
            <input type="text" v-model="form.name" class="w-full border p-2 rounded" required>
          </div>
  
          <!-- Slug (URL) -->
          <div class="mb-4">
            <label class="block font-semibold">Slug (URL)</label>
            <input type="text" v-model="form.slug" class="w-full border p-2 rounded" required>
          </div>
  
          <!-- Plantilla (Solo lectura, no se puede cambiar) -->
          <div class="mb-4">
            <label class="block font-semibold">Plantilla</label>
            <input type="text" :value="page.template.name" class="w-full border p-2 rounded bg-gray-200" disabled>
          </div>
  
          <!-- Campos dinámicos de la plantilla -->
          <div v-for="(field, key) in templateFields" :key="key" class="mb-4">
            <label class="block font-semibold">{{ field.label }}</label>
            <input v-if="field.type === 'text'" type="text" v-model="form.data[key]" class="w-full border p-2 rounded">
            <textarea v-else-if="field.type === 'textarea'" v-model="form.data[key]" class="w-full border p-2 rounded"></textarea>
            <input v-else-if="field.type === 'url'" type="url" v-model="form.data[key]" class="w-full border p-2 rounded">
            <input v-else-if="field.type === 'checkbox'" type="checkbox" v-model="form.data[key]" class="mr-2">
          </div>
  
          <!-- Botones -->
          <div class="flex space-x-4 mt-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
              Guardar Cambios
            </button>
            <Link href="/dashboard/pages" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue';
  import { useForm } from '@inertiajs/vue3';
  import { computed } from 'vue';
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    page: Object
  });
  
  // Inicializar formulario con los datos de la página
  const form = useForm({
    name: props.page.name,
    slug: props.page.slug,
    data: { ...props.page.data }
  });
  
  // Obtener los campos de la plantilla
  const templateFields = computed(() => {
    return props.page.template.fields || {};
  });
  
  // Enviar actualización
  const submit = () => {
    form.put(`/dashboard/pages/${props.page.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Página actualizada correctamente.');
      }
    });
  };
  </script>
  