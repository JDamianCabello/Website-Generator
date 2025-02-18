<template>
    <DashboardLayout>
      <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Crear Página</h1>
  
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block font-semibold">Nombre</label>
            <input type="text" v-model="form.name" class="w-full border p-2 rounded" required>
          </div>
  
          <div class="mb-4">
            <label class="block font-semibold">Slug (URL)</label>
            <input type="text" v-model="form.slug" class="w-full border p-2 rounded" required>
          </div>
  
          <div class="mb-4">
            <label class="block font-semibold">Plantilla</label>
            <select v-model="form.template_id" @change="loadFields" class="w-full border p-2 rounded" required>
              <option v-for="template in templates" :key="template.id" :value="template.id">
                {{ template.name }}
              </option>
            </select>
          </div>
  
          <div v-for="(field, key) in templateFields" :key="key" class="mb-4">
            <label class="block font-semibold">{{ field.label }}</label>
            <input v-if="field.type === 'text'" type="text" v-model="form.data[key]" class="w-full border p-2 rounded">
            <textarea v-else-if="field.type === 'textarea'" v-model="form.data[key]" class="w-full border p-2 rounded"></textarea>
            <input v-else-if="field.type === 'url'" type="url" v-model="form.data[key]" class="w-full border p-2 rounded">
            <input v-else-if="field.type === 'checkbox'" type="checkbox" v-model="form.data[key]" class="mr-2">
          </div>
  
          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Guardar Página
          </button>
        </form>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue';
  import { useForm } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  
  const props = defineProps({ templates: Array });
  
  const form = useForm({
    name: '',
    slug: '',
    template_id: null,
    data: {}
  });
  
  const templateFields = computed(() => {
    const template = props.templates.find(t => t.id === form.template_id);
    return template ? template.fields : {};
  });
  
  const submit = () => {
    form.post('/dashboard/pages');
  };
  </script>
  