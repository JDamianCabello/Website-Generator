<template>
  <DashboardLayout>
    <div>
      <h1>Bienvenido al Dashboard</h1>
      <p>
        Desde aquí puedes administrar el sitio, gestionar usuarios, contenido y más.
      </p>
      <!-- Usar <Link> en lugar de <inertia-link> -->
      <Link href="/dashboard/users" class="btn btn-primary">
        Gestionar Usuarios
      </Link>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3'

export default {
  name: 'DashboardHome',
  components: { DashboardLayout, Link },
};
</script>
