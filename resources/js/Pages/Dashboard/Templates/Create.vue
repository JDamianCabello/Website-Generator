<template>
    <DashboardLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header Section -->
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Nueva Plantilla
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Editor Section -->
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input 
                                    type="text" 
                                    v-model="form.name" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    required
                                />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea 
                                    v-model="form.description" 
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                ></textarea>
                            </div>

                            <!-- Campos Dinámicos -->
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Campos Dinámicos</label>
                                    <button 
                                        type="button"
                                        @click="addField"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200"
                                    >
                                        + Añadir Campo
                                    </button>
                                </div>
                                
                                <div v-for="(field, index) in fields" :key="index" class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nombre del Campo</label>
                                            <input 
                                                type="text" 
                                                v-model="field.name"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                            <select 
                                                v-model="field.type"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            >
                                                <option 
                                                    v-for="type in fieldTypes" 
                                                    :key="type.value" 
                                                    :value="type.value"
                                                >
                                                    {{ type.label }}
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Etiqueta</label>
                                            <input 
                                                type="text" 
                                                v-model="field.label"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            />
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    v-model="field.required"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                                />
                                                <span class="ml-2 text-sm text-gray-600">Campo requerido</span>
                                            </label>
                                            <button 
                                                type="button" 
                                                @click="removeField(index)"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HTML Template -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Plantilla HTML</label>
                                    <button 
                                        type="button"
                                        @click="useExample"
                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        Usar plantilla de ejemplo
                                    </button>
                                </div>
                                <div class="border rounded-lg">
                                    <!-- Tabs -->
                                    <div class="flex border-b">
                                        <button 
                                            type="button"
                                            @click="activeTab = 'code'"
                                            :class="[
                                                'px-4 py-2 text-sm font-medium border-b-2',
                                                activeTab === 'code'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            ]"
                                        >
                                            Código
                                        </button>
                                        <button 
                                            type="button"
                                            @click="updatePreview"
                                            :class="[
                                                'px-4 py-2 text-sm font-medium border-b-2',
                                                activeTab === 'preview'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            ]"
                                        >
                                            Vista Previa
                                        </button>
                                        <button 
                                            type="button"
                                            @click="activeTab = 'ai'"
                                            :class="[
                                                'px-4 py-2 text-sm font-medium border-b-2',
                                                activeTab === 'ai'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            ]"
                                        >
                                            IA Template
                                        </button>
                                    </div>

                                    <!-- Code Panel -->
                                    <div v-show="activeTab === 'code'" class="p-4">
                                        <TemplateEditor v-model="form.template_html" />
                                    </div>

                                    <!-- Preview Panel -->
                                    <div v-show="activeTab === 'preview'" class="relative">
                                        <div v-if="isLoadingPreview" class="flex justify-center items-center py-12">
                                            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <div v-else-if="previewHtml" class="h-[600px] overflow-hidden">
                                            <iframe
                                                ref="previewFrame"
                                                class="w-full h-full"
                                                :srcdoc="previewHtml"
                                            ></iframe>
                                        </div>
                                        <div v-else class="flex justify-center items-center py-12 text-gray-500">
                                            Escribe código HTML para ver la vista previa
                                        </div>
                                    </div>

                                    <!-- AI Template Panel -->
                                    <div v-show="activeTab === 'ai'" class="p-4">
                                        <div class="space-y-4">
                                            <!-- Tipo de Template -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Tipo de Template
                                                </label>
                                                <select 
                                                    v-model="aiTemplate.type" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                >
                                                    <option value="email">Email Marketing</option>
                                                    <option value="landing">Landing Page</option>
                                                    <option value="newsletter">Newsletter</option>
                                                    <option value="form">Formulario</option>
                                                    <option value="custom">Personalizado</option>
                                                </select>
                                            </div>

                                            <!-- Instrucciones para IA -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Instrucciones para la IA
                                                </label>
                                                <textarea
                                                    v-model="aiTemplate.user_instructions"
                                                    rows="3"
                                                    :placeholder="getPromptPlaceholder"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                ></textarea>
                                            </div>

                                            <!-- Opciones Avanzadas -->
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Estilo Visual
                                                    </label>
                                                    <select 
                                                        v-model="aiTemplate.style" 
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    >
                                                        <option value="modern">Moderno</option>
                                                        <option value="minimal">Minimalista</option>
                                                        <option value="corporate">Corporativo</option>
                                                        <option value="creative">Creativo</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Framework
                                                    </label>
                                                    <select 
                                                        v-model="aiTemplate.framework" 
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    >
                                                        <option value="tailwind">Tailwind CSS</option>
                                                        <option value="bootstrap">Bootstrap</option>
                                                        <option value="foundation">Foundation</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Generar Button -->
                                            <div class="flex items-center justify-between pt-4">
                                                <p class="text-sm text-gray-500">
                                                    {{ isGenerating ? 'Generando template...' : 'La IA generará un template basado en tus parámetros' }}
                                                </p>
                                                <button
                                                    type="button"
                                                    @click="generateTemplate"
                                                    :disabled="isGenerating || !canGenerate"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                                >
                                                    <svg 
                                                        v-if="isGenerating" 
                                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                                                        xmlns="http://www.w3.org/2000/svg" 
                                                        fill="none" 
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    {{ isGenerating ? 'Generando...' : 'Generar Template' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Imagen de Preview</label>
                                <input 
                                    type="file" 
                                    @change="form.image = $event.target.files[0]"
                                    accept="image/*"
                                    class="mt-1" 
                                />
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4">
                                <Link
                                    href="/dashboard/templates"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Cancelar
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Guardando...' : 'Guardar Plantilla' }}
                                </button>
                            </div>
                        </form>
                        <!-- Added after the form closing tag -->
                        <div 
                            v-if="previewHtml" 
                            class="p-4 bg-gray-50 border-t"
                        >
                            <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                                <div class="px-4 py-2 bg-gray-100 border-b text-sm font-medium text-gray-700">
                                    Vista Previa en Vivo
                                </div>
                                <iframe
                                    ref="previewFrame"
                                    class="w-full h-[600px]"
                                    :srcdoc="previewHtml"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import axios from 'axios';
import TemplateEditor from '@/Components/TemplateEditor.vue';
import { useToast } from '@/Utils/toast';

const toast = useToast();

// Props
const props = defineProps({
    fieldTypes: {
        type: Array,
        required: true
    },
    exampleTemplate: {
        type: String,
        required: true
    }
});

// Estados base
const activeTab = ref('code');
const previewFrame = ref(null);
const fields = ref([]);
const isLoadingPreview = ref(false);
const previewHtml = ref('');
const isGenerating = ref(false);

// Form principal
const form = useForm({
    name: '',
    description: '',
    fields: {},
    template_html: '',
    image: null,
    default_values: {}
});

// Estado para la generación de IA
const aiTemplate = ref({
    type: 'email',           // email, landing, newsletter, form, custom
    style: 'modern',         // modern, minimal, corporate, creative
    framework: 'tailwind',   // tailwind, bootstrap, foundation
    temperature: 0.7,
    prompt: ''
});

// Computed para el placeholder del prompt
const getPromptPlaceholder = computed(() => {
    const placeholders = {
        email: 'Describe el contenido y estilo del email que deseas generar...',
        landing: 'Describe las secciones y características de la landing page...',
        newsletter: 'Describe el contenido y estructura del newsletter...',
        form: 'Describe los campos y estructura del formulario...',
        custom: 'Describe el template que deseas generar...'
    };
    return placeholders[aiTemplate.value.type] || placeholders.custom;
});

// Computed para validar si podemos generar
const canGenerate = computed(() => {
    return !isGenerating.value;
});

const generateTemplate = async () => {
    if (!canGenerate.value) return;
    
    isGenerating.value = true;
    try {
        const response = await axios.post(route('dashboard.templates.generate'), {
            params: {
                type: aiTemplate.value.type,
                style: aiTemplate.value.style,
                framework: aiTemplate.value.framework,
                temperature: aiTemplate.value.temperature,
                user_instructions: aiTemplate.value.user_instructions
            },
            fields: fields.value
        });
        
        if (response.data.success) {
            form.template_html = response.data.template;
            activeTab.value = 'code';
            toast.success('Template generado correctamente');
        }
    } catch (error) {
        console.error('Error detallado:', error);
        const errorMessage = error.response?.data?.error || 'Error al generar el template';
        toast.error(errorMessage);
    } finally {
        isGenerating.value = false;
    }
};

// Función para actualizar la vista previa
const updatePreview = async () => {
    if (form.template_html) {
        try {
            isLoadingPreview.value = true;
            activeTab.value = 'preview';
            
            const formData = new FormData();
            formData.append('template_html', form.template_html);
            formData.append('fields', JSON.stringify(form.fields));
            formData.append('default_values', JSON.stringify(form.default_values));
            
            const response = await axios.post(route('dashboard.templates.previewDraft'), formData);
            
            if (response.data.error) {
                throw new Error(response.data.error);
            }
            
            previewHtml.value = response.data;
        } catch (error) {
            console.error('Error al generar preview:', error);
            previewHtml.value = `
                <div class="p-4">
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Error al generar la vista previa</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    ${error.response?.data?.error || error.message}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        } finally {
            isLoadingPreview.value = false;
        }
    }
};

// Añadir campo
const addField = () => {
    fields.value.push({
        name: '',
        type: 'text',
        label: '',
        required: false
    });
};

// Eliminar campo
const removeField = (index) => {
    fields.value.splice(index, 1);
};

// Usar plantilla de ejemplo
const useExample = () => {
    form.template_html = props.exampleTemplate;
};

// Observar cambios en los campos
watch(fields, (newFields) => {
    const fieldsObject = {};
    const defaultValues = {};
    
    newFields.forEach(field => {
        if (field.name) {
            fieldsObject[field.name] = {
                type: field.type,
                label: field.label,
                required: field.required
            };
            
            // Establecer valores por defecto según el tipo
            switch(field.type) {
                case 'text':
                case 'textarea':
                    defaultValues[field.name] = `${field.label}`;
                    break;
                case 'email':
                    defaultValues[field.name] = 'ejemplo@email.com';
                    break;
                case 'url':
                    defaultValues[field.name] = '#';
                    break;
                case 'boolean':
                    defaultValues[field.name] = true;
                    break;
                case 'multiselect':
                    defaultValues[field.name] = ['Opción 1', 'Opción 2'];
                    break;
            }
        }
    });
    
    form.fields = fieldsObject;
    form.default_values = defaultValues;
}, { deep: true });

// Observar cambios en el HTML para actualizar la vista previa
watch(() => form.template_html, () => {
    if (activeTab.value === 'preview') {
        updatePreview();
    }
});

// Enviar formulario
const submit = () => {
    form.post(route('dashboard.templates.store'), {
        onSuccess: () => {
            toast.success('Template guardado correctamente');
        },
        onError: () => {
            toast.error('Error al guardar el template');
        },
        preserveScroll: true
    });
};
</script>