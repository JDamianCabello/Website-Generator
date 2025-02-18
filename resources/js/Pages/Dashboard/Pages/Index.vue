<template>
    <DashboardLayout>
      <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900">Gestión de Páginas</h1>
          <Link 
            href="/dashboard/pages/create" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Crear Página
          </Link>
        </div>
  
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plantilla</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="page in pages" :key="page.id">
                <td class="px-6 py-4">{{ page.name }}</td>
                <td class="px-6 py-4">{{ page.template.name }}</td>
                <td class="px-6 py-4">
                  <Link :href="`/pages/${page.slug}`" class="text-blue-600 hover:underline">Ver</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue';
  import { Link } from '@inertiajs/vue3';
  
  defineProps({ pages: Array });
  </script>
  