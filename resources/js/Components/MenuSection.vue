<template>
    <div class="menu-section">
      <!-- Título de la sección con icono de despliegue -->
      <button 
        @click="$emit('toggle')" 
        class="w-full flex items-center justify-between px-4 py-2 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-700 hover:text-white group"
      >
        <span class="font-medium">{{ title }}</span>
        <svg 
          class="w-4 h-4 transition-transform duration-200"
          :class="{ 'rotate-180': isOpen }"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
  
      <!-- Contenido de la sección (elementos del menú) -->
      <div 
        v-show="isOpen"
        class="mt-2 space-y-1 transition-all duration-200"
      >
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      required: true
    },
    isOpen: {
      type: Boolean,
      default: false
    }
  });
  
  defineEmits(['toggle']);
  </script>