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
                  {{ template.name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">{{ template.description }}</p>
              </div>
            </div>
          </div>
  
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Editor Section -->
            <div class="p-6 border-r border-gray-200">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900">Editor de Campos</h2>
                <button 
                  @click="updatePreview"
                  :disabled="isUpdating"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="!isUpdating" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isUpdating ? 'Actualizando...' : 'Actualizar Vista Previa' }}
                </button>
              </div>
  
              <form @submit.prevent="updatePreview" class="space-y-6">
                <div 
                  v-for="field in fieldsArray" 
                  :key="field.key" 
                  class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                >
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ field.label }}
                  </label>
                  
                  <!-- Boolean Field -->
                  <template v-if="field.type === 'boolean'">
                    <div class="relative flex items-start">
                      <div class="flex items-center h-5">
                        <input 
                          :id="field.key"
                          type="checkbox"
                          v-model="formData[field.key]"
                          class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                      </div>
                      <div class="ml-3 text-sm">
                        <label :for="field.key" class="text-gray-500">
                          {{ field.description || `Activar ${field.label}` }}
                        </label>
                      </div>
                    </div>
                  </template>
  
                  <!-- Multiselect Field -->
                  <template v-else-if="field.type === 'multiselect'">
                    <textarea 
                      :id="field.key"
                      v-model="multiselectString[field.key]"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      :placeholder="`Ingrese valores separados por coma`"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Separe los valores con comas</p>
                  </template>
  
                  <!-- Text Field -->
                  <template v-else>
                    <input 
                      :id="field.key"
                      type="text"
                      v-model="formData[field.key]"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                  </template>
                </div>
              </form>
            </div>
  
            <!-- Preview Section -->
            <div class="p-6">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900">Vista Previa</h2>
                <button 
                  @click="openInNewTab"
                  class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                  </svg>
                  Abrir en Nueva Pestaña
                </button>
              </div>
              
              <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md">
                <iframe 
                  ref="previewIframe"
                  class="w-full h-[800px] border-0" 
                  :srcdoc="renderedTemplate"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import { ref, reactive, computed, watch, onMounted } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import DashboardLayout from '@/Layouts/DashboardLayout.vue'
  
  const props = defineProps({
    template: Object,
    defaultValues: Object,
    currentValues: Object,
    renderedTemplate: String
  })
  
  const previewIframe = ref(null)
  const isUpdating = ref(false)
  const formData = reactive({})
  const multiselectString = reactive({})
  
  // Computed para obtener los campos de la plantilla
  const fieldsArray = computed(() => {
    const fields = props.template.fields || {}
    return Object.entries(fields).map(([key, field]) => ({ key, ...field }))
  })
  
  // Función para procesar valores según su tipo
  const processFieldValue = (value, type) => {
    switch (type) {
      case 'boolean':
        return value === true || value === 'true'
      case 'multiselect':
        if (typeof value === 'string') {
          return value.split(',').map(v => v.trim()).filter(Boolean)
        }
        return Array.isArray(value) ? value : []
      default:
        return value ?? ''
    }
  }
  
  // Función para inicializar el formulario
  const initializeFormData = (values) => {
    if (!values) return
  
    fieldsArray.value.forEach(field => {
      const value = values[field.key]
      formData[field.key] = processFieldValue(value, field.type)
      
      if (field.type === 'multiselect') {
        multiselectString[field.key] = Array.isArray(formData[field.key]) 
          ? formData[field.key].join(', ') 
          : ''
      }
    })
    
    console.log('Form data initialized:', formData)
  }
  
  // Inicializar al montar el componente
  onMounted(() => {
    console.log('Current Values:', props.currentValues)
    console.log('Default Values:', props.defaultValues)
    initializeFormData(props.currentValues || props.defaultValues)
  })
  
  // Observar cambios en currentValues
  watch(() => props.currentValues, (newValues) => {
    if (newValues) {
      console.log('Current values updated:', newValues)
      initializeFormData(newValues)
    }
  }, { deep: true })
  
  // Observar cambios en multiselect
  watch(multiselectString, (newVal) => {
    for (const key in newVal) {
      formData[key] = newVal[key].split(',').map(s => s.trim()).filter(Boolean)
    }
  }, { deep: true })
  
  // Función para preparar payload
  const preparePayload = () => {
    const payload = {}
    fieldsArray.value.forEach(field => {
      switch (field.type) {
        case 'boolean':
          payload[field.key] = formData[field.key] ? 'true' : 'false'
          break
        case 'multiselect':
          payload[field.key] = Array.isArray(formData[field.key]) 
            ? formData[field.key].join(',') 
            : formData[field.key]
          break
        default:
          payload[field.key] = formData[field.key]
      }
    })
    return payload
  }
  
  // Función para actualizar la vista previa
  const updatePreview = async () => {
    isUpdating.value = true
    const payload = preparePayload()
    console.log('Sending payload:', payload)
    
    try {
      await Inertia.get(
        route('dashboard.templates.show', { template: props.template.id }),
        payload,
        { 
          preserveState: true,
          preserveScroll: true,
          replace: true,
          onSuccess: () => {
            console.log('Preview updated successfully')
          },
          onError: (errors) => {
            console.error('Error updating preview:', errors)
          }
        }
      )
    } finally {
      isUpdating.value = false
    }
  }
  
// Función para abrir en nueva pestaña
const openInNewTab = () => {
  const payload = preparePayload()
  const queryString = new URLSearchParams(payload).toString()
  const url = `${route('dashboard.templates.preview', { template: props.template.id })}?${queryString}`
  window.open(url, '_blank')
}
  </script>