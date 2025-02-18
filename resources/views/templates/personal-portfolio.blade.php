<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-bold">{{ $name }}</h1>
            <h2 class="text-2xl mt-4">{{ $professionalTitle }}</h2>
            <p class="max-w-2xl mx-auto mt-6 text-lg">{{ $description }}</p>
        </div>
    </header>

    <section class="py-16 bg-white">
        <div class="container mx-auto">
            <h3 class="text-3xl font-bold text-center mb-10">Tecnologías Principales</h3>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($technologies as $tech)
                    <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full">
                        {{ $tech }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    @if($showContact)
    <section class="bg-gray-200 py-16">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-bold mb-6">Contáctame</h3>
            <div class="flex justify-center space-x-6">
                @if($email)
                    <a href="mailto:{{ $email }}" class="text-blue-600 hover:text-blue-800">
                        Email
                    </a>
                @endif
                
                @if($linkedinUrl)
                    <a href="{{ $linkedinUrl }}" target="_blank" class="text-blue-600">
                        LinkedIn
                    </a>
                @endif
            </div>
        </div>
    </section>
    @endif
</body>
</html>