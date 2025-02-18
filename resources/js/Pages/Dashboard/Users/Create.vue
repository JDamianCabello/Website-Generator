<template>
  <DashboardLayout>
    <div class="max-w-6xl mx-auto p-6">
      <div class="bg-white rounded-lg shadow-xl">
        <div class="p-6 border-b border-gray-200">
          <h1 class="text-2xl font-bold text-gray-900">Crear Nueva Plantilla</h1>
        </div>

        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <!-- Información básica -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nombre</label>
              <input
                type="text"
                v-model="form.name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Descripción</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Ruta de la Vista</label>
              <input
                type="text"
                v-model="form.view_path"
                placeholder="templates.nombre-template"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Vista Blade</label>
              <textarea
                v-model="form.template_html"
                rows="10"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Imagen de Preview</label>
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="mt-1 block w-full"
              />
            </div>
          </div>

          <!-- Campos Dinámicos -->
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Campos Dinámicos</h3>
              <button
                type="button"
                @click="addField"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
              >
                Añadir Campo
              </button>
            </div>

            <div v-for="(field, index) in fields" :key="index" class="p-4 border rounded-lg space-y-4">
              <div class="flex justify-between">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Clave del campo -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Clave</label>
                    <input
                      type="text"
                      v-model="field.key"
                      @input="updateFields"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>

                  <!-- Tipo de campo -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select
                      v-model="field.type"
                      @change="updateFields"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="text">Texto</option>
                      <option value="textarea">Área de texto</option>
                      <option value="email">Email</option>
                      <option value="url">URL</option>
                      <option value="boolean">Booleano</option>
                      <option value="multiselect">Multiselección</option>
                    </select>
                  </div>

                  <!-- Etiqueta del campo -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Etiqueta</label>
                    <input
                      type="text"
                      v-model="field.label"
                      @input="updateFields"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                </div>

                <button
                  type="button"
                  @click="removeField(index)"
                  class="ml-4 text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>

              <!-- Opciones adicionales según el tipo -->
              <div class="space-y-4">
                <!-- Required -->
                <div class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="field.required"
                    @change="updateFields"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                  <label class="ml-2 block text-sm text-gray-900">Requerido</label>
                </div>

                <!-- Opciones para multiselect -->
                <div v-if="field.type === 'multiselect'" class="space-y-2">
                  <label class="block text-sm font-medium text-gray-700">Opciones</label>
                  <div class="flex items-center space-x-2">
                    <input
                      type="text"
                      v-model="newOption"
                      @keyup.enter="addOption(index)"
                      placeholder="Nueva opción"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <button
                      type="button"
                      @click="addOption(index)"
                      class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                    >
                      Añadir
                    </button>
                  </div>
                  <div class="flex flex-wrap gap-2">
                    <div
                      v-for="(option, optionIndex) in field.options"
                      :key="optionIndex"
                      class="inline-flex items-center px-2 py-1 rounded-full text-sm bg-gray-200"
                    >
                      {{ option }}
                      <button
                        type="button"
                        @click="removeOption(index, optionIndex)"
                        class="ml-1 text-gray-500 hover:text-gray-700"
                      >
                        ×
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Valor por defecto -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Valor por defecto</label>
                  <div v-if="field.type === 'boolean'">
                    <input
                      type="checkbox"
                      v-model="field.default_value"
                      @change="updateDefaultValues"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                  <div v-else-if="field.type === 'multiselect'">
                    <select
                      v-model="field.default_value"
                      multiple
                      @change="updateDefaultValues"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option v-for="option in field.options" :key="option" :value="option">
                        {{ option }}
                      </option>
                    </select>
                  </div>
                  <div v-else>
                    <input
                      :type="field.type"
                      v-model="field.default_value"
                      @input="updateDefaultValues"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end space-x-4">
            <Link
              href="/dashboard/templates"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancelar
            </Link>
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
            >
              Guardar Plantilla
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { TrashIcon } from '@heroicons/vue/24/outline';

const form = reactive({
  name: '',
  description: '',
  view_path: '',
  fields: {},
  default_values: {},
  template_html: '',
  image: null
});

const fields = ref([]);
const newOption = ref('');

const addField = () => {
  fields.value.push({
    key: '',
    type: 'text',
    label: '',
    required: false,
    options: [],
    default_value: ''
  });
};

const removeField = (index) => {
  fields.value.splice(index, 1);
  updateFields();
};

const addOption = (fieldIndex) => {
  if (newOption.value.trim()) {
    fields.value[fieldIndex].options.push(newOption.value.trim());
    newOption.value = '';
    updateFields();
  }
};

const removeOption = (fieldIndex, optionIndex) => {
  fields.value[fieldIndex].options.splice(optionIndex, 1);
  updateFields();
};

const updateFields = () => {
  const fieldsObject = {};
  fields.value.forEach(field => {
    if (field.key) {
      fieldsObject[field.key] = {
        type: field.type,
        label: field.label,
        required: field.required,
        options: field.type === 'multiselect' ? field.options : undefined
      };
    }
  });
  form.fields = fieldsObject;
};

const updateDefaultValues = () => {
  const defaultValues = {};
  fields.value.forEach(field => {
    if (field.key && field.default_value !== undefined) {
      defaultValues[field.key] = field.default_value;
    }
  });
  form.default_values = defaultValues;
};

const handleImageUpload = (event) => {
  form.image = event.target.files[0];
};

const handleSubmit = () => {
  router.post('/dashboard/templates', form, {
    forceFormData: true
  });
};
</script>