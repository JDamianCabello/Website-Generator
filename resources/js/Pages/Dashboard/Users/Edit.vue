<template>
  <DashboardLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
          Editar Usuario: {{ user.name }}
        </h1>
        <p class="mt-2 text-gray-600">
          Modifica los detalles del usuario y sus roles asignados.
        </p>
      </div>

      <!-- Contenido Principal -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form @submit.prevent="submit">
          <div class="p-6 space-y-6">
            <!-- Información Personal -->
            <div class="space-y-6">
              <h2 class="text-lg font-medium text-gray-900 pb-2 border-b">
                Información Personal
              </h2>
              
              <!-- Nombre -->
              <div>
                <label class="block font-medium text-gray-700">Nombre</label>
                <input
                  type="text"
                  v-model="form.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm 
                         focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                         transition duration-150 ease-in-out"
                  :class="{ 'border-red-300': errors.name }"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
              </div>

              <!-- Email -->
              <div>
                <label class="block font-medium text-gray-700">Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                  </div>
                  <input
                    type="email"
                    v-model="form.email"
                    class="block w-full pl-10 border-gray-300 rounded-md shadow-sm 
                           focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                           transition duration-150 ease-in-out"
                    :class="{ 'border-red-300': errors.email }"
                  />
                </div>
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
              </div>
            </div>

            <!-- Roles y Permisos -->
            <div class="space-y-6">
              <h2 class="text-lg font-medium text-gray-900 pb-2 border-b">
                Roles y Estado
              </h2>

              <!-- Roles -->
              <div>
                <label class="block font-medium text-gray-700">Roles Asignados</label>
                <div class="mt-1">
                  <select
                    v-model="form.roles"
                    multiple
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 
                           focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                           rounded-md transition duration-150 ease-in-out"
                    :class="{ 'border-red-300': errors.roles }"
                  >
                    <option v-for="role in availableRoles" :key="role.id" :value="role.id">
                      {{ role.name }}
                    </option>
                  </select>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                  Mantén presionado Ctrl (⌘ en Mac) para seleccionar múltiples roles
                </p>
                <p v-if="errors.roles" class="mt-1 text-sm text-red-600">{{ errors.roles }}</p>
              </div>

              <!-- Estado -->
              <div>
                <label class="block font-medium text-gray-700">Estado de la Cuenta</label>
                <div class="mt-1">
                  <select
                    v-model="form.is_blocked"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 
                           focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                           rounded-md transition duration-150 ease-in-out"
                  >
                    <option :value="false">🟢 Activo</option>
                    <option :value="true">🔴 Bloqueado</option>
                  </select>
                </div>
                <p v-if="errors.is_blocked" class="mt-1 text-sm text-red-600">{{ errors.is_blocked }}</p>
              </div>
            </div>
          </div>

          <!-- Footer con Botones -->
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <Link
              href="/dashboard/users"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 
                     bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 
                     focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              Cancelar
            </Link>
            
            <button
              type="submit"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm 
                     text-white bg-blue-600 hover:bg-blue-700 focus:outline-none 
                     focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                     transition-colors disabled:opacity-50 disabled:cursor-not-allowed
                     inline-flex items-center"
              :disabled="form.processing"
            >
              <svg 
                v-if="form.processing" 
                class="animate-spin -ml-1 mr-2 h-4 w-4" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" 
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
              </svg>
              {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useToast } from '@/Utils/toast';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  availableRoles: {
    type: Array,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  }
});

const toast = useToast();

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  roles: props.user.roles.map(role => role.id),
  is_blocked: Boolean(props.user.is_blocked),
});

const submit = () => {
  form.put(`/dashboard/users/${props.user.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Usuario actualizado correctamente');
    },
    onError: (errors) => {
      toast.error(errors.error || 'Error al actualizar el usuario');
    },
  });
};
</script>