<template>
  <div class="min-h-screen flex flex-col">
    <div class="flex-1 flex">
      <!-- Sidebar -->
      <SidebarMenu />

      <!-- Contenido Principal con scroll independiente -->
      <div class="flex-1 flex flex-col bg-gray-50">
        <main class="flex-1 p-6 overflow-auto">
          <slot></slot>
        </main>

        <!-- Footer siempre abajo -->
        <DashboardFooter />
      </div>
    </div>
  </div>
</template>

<script setup>
import SidebarMenu from '@/Components/SidebarMenu.vue';
import DashboardFooter from '@/Components/DashboardFooter.vue';
</script>