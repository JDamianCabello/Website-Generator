<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Página de Ejemplo' }}</title>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $title ?? 'Página de Ejemplo' }}
            </h1>
            <!-- Navegación -->
            <nav class="mt-4">
                <ul class="flex space-x-4">
                    <li><a href="#" class="text-gray-700 hover:text-blue-500">Inicio</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-blue-500">Servicios</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-blue-500">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">
                {{ $subtitle ?? 'Bienvenido a mi sitio' }}
            </h2>
            <p class="text-xl text-white/80">
                {{ $description ?? 'Esta es una descripción de ejemplo para la sección hero.' }}
            </p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Característica 1</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Característica 2</h3>
                <p class="text-gray-600">Sed do eiusmod tempor incididunt ut labore et dolore.</p>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Característica 3</h3>
                <p class="text-gray-600">Ut enim ad minim veniam, quis nostrud exercitation.</p>
            </div>
        </div>
    </div>

    <!-- Sección de Llamado a la Acción -->
    <section class="bg-indigo-700 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold text-white">¡Únete a nuestra comunidad!</h3>
            <p class="text-white mt-4">Suscríbete a nuestro boletín para recibir las últimas actualizaciones.</p>
            <form class="mt-6 flex justify-center">
                <input type="email" placeholder="Tu correo electrónico" class="px-4 py-2 rounded-l-md border-0">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-r-md">Suscribirse</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center">
            <p>&copy; 2025 Tu Empresa. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
