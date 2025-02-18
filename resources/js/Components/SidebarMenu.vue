<template>
  <aside class="bg-gray-800 text-gray-300 w-64 min-h-screen p-5 hidden lg:flex flex-col space-y-4 border-r border-gray-700 shadow-xl">
    <h2 class="text-xl font-bold text-white mb-6 px-2 flex items-center">
      <span class="inline-block w-2 h-6 bg-indigo-500 rounded mr-3"></span>
      Menú
    </h2>

    <!-- Resumen siempre visible -->
    <Link 
      href="/dashboard" 
      class="flex items-center px-4 py-2 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-700 hover:text-white group"
    >
      <span class="mr-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
      </span>
      Resumen
    </Link>

    <!-- Secciones del menú -->
    <div class="space-y-2">
      <template v-for="section in menuSections" :key="section.title">
        <MenuSection
          v-if="hasPermission(section.permission)"
          :title="section.title"
          :isOpen="menuState[section.id]"
          @toggle="toggleMenu(section.id)"
          class="border-b border-gray-700 pb-2 last:border-0"
        >
          <template v-for="item in section.items" :key="item.path">
            <Link 
              v-if="hasPermission(item.permission)"
              :href="item.path"
              class="menu-item pl-8 text-sm text-gray-400 hover:text-white flex items-center space-x-2 py-2 px-4 rounded-lg transition-all duration-200 hover:bg-gray-700 group"
            >
              <span class="w-2 h-2 rounded-full bg-gray-600 group-hover:bg-indigo-500 transition-colors"></span>
              <span>{{ item.label }}</span>
            </Link>
          </template>
        </MenuSection>
      </template>
    </div>

    <!-- Footer del sidebar -->
    <div class="mt-auto pt-6 border-t border-gray-700">
      <div class="px-4 py-2 text-sm text-gray-400">
        <p class="font-semibold text-gray-300">Panel de Administración</p>
        <p class="mt-1">v1.0.0</p>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import MenuSection from '@/Components/MenuSection.vue';

// Estado del menú usando un objeto
const menuState = ref({
  users: false,
  roles: false,
  pages: false,
  templates: false,
  blog: false,
  settings: false,
});

// Obtener permisos del usuario
const permissions = computed(() => 
  usePage().props.auth.user?.permissions || []
);

// Verificar si tiene permiso
const hasPermission = (permission) => {
  return permission ? permissions.value.includes(permission) : true;
};

// Función para alternar menús
const toggleMenu = (menuId) => {
  menuState.value[menuId] = !menuState.value[menuId];
};

// Configuración centralizada del menú
const menuSections = [
  {
    id: 'users',
    title: 'Usuarios',
    permission: 'view users',
    items: [
      { label: 'Lista de Usuarios', path: '/dashboard/users', permission: 'view users' },
      { label: 'Crear Usuario', path: '/dashboard/users/create', permission: 'create users' }
    ]
  },
  {
    id: 'roles',
    title: 'Roles y Permisos',
    permission: 'view roles',
    items: [
      { label: 'Gestionar Roles', path: '/dashboard/roles', permission: 'view roles' },
      { label: 'Crear Rol', path: '/dashboard/roles/create', permission: 'create roles' }
    ]
  },
  {
    id: 'pages',
    title: 'Gestión de Páginas',
    permission: 'view pages',
    items: [
      { label: 'Lista de Páginas', path: '/dashboard/pages', permission: 'view pages' },
      { label: 'Crear Página', path: '/dashboard/pages/create', permission: 'create pages' }
    ]
  },
  {
    id: 'templates',
    title: 'Gestión de Templates',
    permission: 'view templates',
    items: [
      { label: 'Lista de Templates', path: '/dashboard/templates', permission: 'view templates' },
      { label: 'Crear Template', path: '/dashboard/templates/create', permission: 'create templates' }
    ]
  },
  {
    id: 'blog',
    title: 'Blog',
    permission: 'view posts',
    items: [
      { label: 'Lista de Posts', path: '/dashboard/posts', permission: 'view posts' },
      { label: 'Crear Post', path: '/dashboard/posts/create', permission: 'create posts' }
    ]
  },
  {
    id: 'settings',
    title: 'Configuración',
    permission: 'view settings',
    items: [
      { label: 'Editar Notificaciones', path: '/dashboard/web/toast/show', permission: 'edit settings' },
      { label: 'Ajustes Generales', path: '/dashboard/settings', permission: 'manage site configuration' }
    ]
  }
];
</script>
