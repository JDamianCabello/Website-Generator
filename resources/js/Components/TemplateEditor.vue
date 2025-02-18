<template>
    <div class="relative">
      <!-- Editor CodeMirror -->
      <Codemirror
        v-model:value="internalValue"
        :options="cmOptions"
        border
        placeholder="Escribe el código HTML aquí..."
        :height="300"
      />
    
      <!-- Botón de configuración (engranaje) -->
      <button
        @click="toggleModal"
        class="absolute top-2 right-2 p-2 bg-gray-100 rounded-full shadow-md hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-6 h-6 text-gray-600"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.503 1.503 0 001.95 1.027l.942-.342a1.5 1.5 0 012.179 1.683l-.342.942a1.503 1.503 0 001.027 1.95c.921.3.921 1.603 0 1.902a1.503 1.503 0 00-1.027 1.95l.342.942a1.5 1.5 0 01-1.683 2.179l-.942-.342a1.503 1.503 0 00-1.95 1.027c-.3.921-1.603.921-1.902 0a1.503 1.503 0 00-1.95-1.027l-.942.342a1.5 1.5 0 01-2.179-1.683l.342-.942a1.503 1.503 0 00-1.027-1.95c-.921-.3-.921-1.603 0-1.902a1.503 1.503 0 001.027-1.95l-.342-.942a1.5 1.5 0 011.683-2.179l.942.342a1.503 1.503 0 001.95-1.027z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
          />
        </svg>
      </button>
    
      <!-- Modal de Configuración -->
      <transition 
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div 
          v-if="showModal" 
          class="fixed inset-0 flex items-center justify-center z-50 p-4"
        >
          <!-- Fondo semitransparente -->
          <div 
            class="absolute inset-0 bg-black/50 backdrop-blur-sm" 
            @click="toggleModal"
          ></div>
          
          <!-- Contenido del modal -->
          <div 
            class="bg-white rounded-xl shadow-2xl z-10 p-6 w-full max-w-md mx-auto transform transition-all relative"
          >
            <!-- Botón de cierre -->
            <button 
              @click="toggleModal"
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M6 18L18 6M6 6l12 12" 
                />
              </svg>
            </button>
  
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
              Configuración del Editor
            </h2>
            
            <!-- Opciones básicas -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Mostrar Números de Línea
                </label>
                <div class="flex items-center">
                  <input 
                    type="checkbox" 
                    v-model="config.lineNumbers" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm text-gray-600">
                    Activar números de línea
                  </span>
                </div>
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Tema
                </label>
                <select 
                  v-model="config.theme" 
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                  <option 
                    v-for="theme in themes" 
                    :key="theme" 
                    :value="theme"
                  >
                    {{ theme }}
                  </option>
                </select>
              </div>
  
              <!-- Opciones avanzadas -->
              <div class="border-t border-gray-200 pt-4 mt-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                  Configuración de Indentación
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Indent Unit
                    </label>
                    <input
                      type="number"
                      v-model.number="config.indentUnit"
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Tamaño de Tabulación
                    </label>
                    <input
                      type="number"
                      v-model.number="config.tabSize"
                      min="1"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                    />
                  </div>
                </div>
  
                <div class="mt-4 space-y-3">
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      v-model="config.smartIndent" 
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <span class="ml-2 text-sm text-gray-600">
                      Indentación Inteligente
                    </span>
                  </div>
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      v-model="config.lineWrapping" 
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <span class="ml-2 text-sm text-gray-600">
                      Ajuste de Línea
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { computed, reactive, ref } from 'vue'
  import Codemirror from 'codemirror-editor-vue3'
  
  // Importar addons y modo necesarios
  import 'codemirror/addon/display/placeholder.js'
  import 'codemirror/mode/htmlmixed/htmlmixed.js'
  
  // Importar addons y modo necesarios
  import 'codemirror/addon/display/placeholder.js'
  import 'codemirror/mode/htmlmixed/htmlmixed.js'
  
  // Preimportar TODOS los temas disponibles
  import 'codemirror/theme/3024-day.css'
  import 'codemirror/theme/3024-night.css'
  import 'codemirror/theme/abbott.css'
  import 'codemirror/theme/abcdef.css'
  import 'codemirror/theme/ambiance-mobile.css'
  import 'codemirror/theme/ambiance.css'
  import 'codemirror/theme/ayu-dark.css'
  import 'codemirror/theme/ayu-mirage.css'
  import 'codemirror/theme/base16-dark.css'
  import 'codemirror/theme/base16-light.css'
  import 'codemirror/theme/bespin.css'
  import 'codemirror/theme/blackboard.css'
  import 'codemirror/theme/cobalt.css'
  import 'codemirror/theme/colorforth.css'
  import 'codemirror/theme/darcula.css'
  import 'codemirror/theme/dracula.css'
  import 'codemirror/theme/duotone-dark.css'
  import 'codemirror/theme/duotone-light.css'
  import 'codemirror/theme/eclipse.css'
  import 'codemirror/theme/elegant.css'
  import 'codemirror/theme/erlang-dark.css'
  import 'codemirror/theme/gruvbox-dark.css'
  import 'codemirror/theme/hopscotch.css'
  import 'codemirror/theme/icecoder.css'
  import 'codemirror/theme/idea.css'
  import 'codemirror/theme/isotope.css'
  import 'codemirror/theme/juejin.css'
  import 'codemirror/theme/lesser-dark.css'
  import 'codemirror/theme/liquibyte.css'
  import 'codemirror/theme/lucario.css'
  import 'codemirror/theme/material-darker.css'
  import 'codemirror/theme/material-ocean.css'
  import 'codemirror/theme/material-palenight.css'
  import 'codemirror/theme/material.css'
  import 'codemirror/theme/mbo.css'
  import 'codemirror/theme/mdn-like.css'
  import 'codemirror/theme/midnight.css'
  import 'codemirror/theme/monokai.css'
  import 'codemirror/theme/moxer.css'
  import 'codemirror/theme/neat.css'
  import 'codemirror/theme/neo.css'
  import 'codemirror/theme/night.css'
  import 'codemirror/theme/nord.css'
  import 'codemirror/theme/oceanic-next.css'
  import 'codemirror/theme/panda-syntax.css'
  import 'codemirror/theme/paraiso-dark.css'
  import 'codemirror/theme/paraiso-light.css'
  import 'codemirror/theme/pastel-on-dark.css'
  import 'codemirror/theme/railscasts.css'
  import 'codemirror/theme/rubyblue.css'
  import 'codemirror/theme/seti.css'
  import 'codemirror/theme/shadowfox.css'
  import 'codemirror/theme/solarized.css'
  import 'codemirror/theme/ssms.css'
  import 'codemirror/theme/the-matrix.css'
  import 'codemirror/theme/tomorrow-night-bright.css'
  import 'codemirror/theme/tomorrow-night-eighties.css'
  import 'codemirror/theme/ttcn.css'
  import 'codemirror/theme/twilight.css'
  import 'codemirror/theme/vibrant-ink.css'
  import 'codemirror/theme/xq-dark.css'
  import 'codemirror/theme/xq-light.css'
  import 'codemirror/theme/yeti.css'
  import 'codemirror/theme/yonce.css'
  import 'codemirror/theme/zenburn.css'
  
  // Lista de temas disponibles 
  const themes = [
    'default',
    'dracula',
    '3024-day',
    '3024-night',
    'abbott',
    'abcdef',
    'ambiance',
    'ayu-dark',
    'ayu-mirage',
    'base16-dark',
    'base16-light',
    'bespin',
    'blackboard',
    'cobalt',
    'colorforth',
    'darcula',
    'dracula', // repetido para compatibilidad
    'duotone-dark',
    'duotone-light',
    'eclipse',
    'elegant',
    'erlang-dark',
    'gruvbox-dark',
    'hopscotch',
    'icecoder',
    'idea',
    'isotope',
    'juejin',
    'lesser-dark',
    'liquibyte',
    'lucario',
    'material-darker',
    'material-ocean',
    'material-palenight',
    'material',
    'mbo',
    'mdn-like',
    'midnight',
    'monokai',
    'moxer',
    'neat',
    'neo',
    'night',
    'nord',
    'oceanic-next',
    'panda-syntax',
    'paraiso-dark',
    'paraiso-light',
    'pastel-on-dark',
    'railscasts',
    'rubyblue',
    'seti',
    'shadowfox',
    'solarized dark',
    'solarized light',
    'the-matrix',
    'tomorrow-night-bright',
    'tomorrow-night-eighties',
    'ttcn',
    'twilight',
    'vibrant-ink',
    'xq-dark',
    'xq-light',
    'yeti',
    'yonce',
    'zenburn'
  ]
  
  // Configuración reactiva del editor
  const config = reactive({
    mode: 'htmlmixed',
    theme: 'default', // Tema por defecto
    lineNumbers: true,
    indentUnit: 2,
    tabSize: 4,
    smartIndent: true,
    lineWrapping: false
  })
  
  // Las opciones del editor se derivan de la configuración
  const cmOptions = computed(() => ({
    mode: config.mode,
    theme: config.theme,
    lineNumbers: config.lineNumbers,
    indentUnit: config.indentUnit,
    tabSize: config.tabSize,
    smartIndent: config.smartIndent,
    lineWrapping: config.lineWrapping
  }))
  
  // Control de visibilidad del modal
  const showModal = ref(false)
  const toggleModal = () => {
    showModal.value = !showModal.value
  }
  
  // v-model bidireccional para el contenido del editor
  const props = defineProps({
    modelValue: {
      type: String,
      required: true
    }
  })
  const emit = defineEmits(['update:modelValue'])
  const internalValue = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
  })
  </script>
  
  <style scoped>
  /* Agrega estilos personalizados si lo necesitas */
  </style>