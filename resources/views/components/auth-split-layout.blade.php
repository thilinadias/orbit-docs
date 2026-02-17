<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>OrbitDocs</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col md:flex-row">
            
            <!-- Left Side: Branding & Background -->
            <div class="w-full md:w-1/2 lg:w-3/5 bg-gray-900 relative overflow-hidden flex flex-col justify-center items-center text-white p-12">
                <!-- Background Decoration (Gradients/Circles) -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 opacity-90"></div>
                <!-- Decorative Circles -->
                <div class="absolute top-0 left-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col items-center text-center max-w-lg">
                    <!-- Logo -->
                    <div class="mb-8 p-4 bg-white/10 backdrop-blur-sm rounded-2xl shadow-xl">
                        @if($logo = \App\Models\Setting::get('system_logo'))
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="w-24 h-24 object-contain">
                        @else
                            <x-application-logo class="w-24 h-24 fill-current text-white" />
                        @endif
                    </div>
                    
                    <!-- System Name -->
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300">
                        {{ \App\Models\Setting::get('system_name', 'OrbitDocs') }}
                    </h1>
                    
                    <!-- Tagline/Description -->
                    <p class="text-lg text-gray-300 mb-8 font-light">
                        {{ \App\Models\Setting::get('system_description', 'Secure Documentation & Asset Management for MSPs.') }}
                    </p>

                    <p class="text-sm text-gray-400 font-medium">
                        Login into your account to access the dashboard.
                    </p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-1/2 lg:w-2/5 flex items-center justify-center p-8 bg-white dark:bg-gray-800">
                <div class="w-full max-w-md space-y-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $title ?? 'Welcome Back' }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $description ?? 'Please enter your details to sign in.' }}
                        </p>
                    </div>

                    {{ $slot }}
                    
                    <div class="mt-8 text-center text-xs text-gray-400 flex flex-col items-center">
                        <p class="mb-2">&copy; {{ date('Y') }} OrbitDocs. All rights reserved.</p>
                        <p class="flex items-center space-x-1">
                            <span>Made with</span>
                            <span class="text-red-500">❤️</span>
                            <span>by <a href="https://www.linkedin.com/in/thilinaadias/" target="_blank" class="hover:text-purple-600 transition-colors">Thilina Dias</a></span>
                        </p>
                        <div class="mt-3 flex space-x-4">
                            <a href="https://github.com/thilinadias/" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <span class="sr-only">GitHub</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                            </a>
                            <a href="https://www.linkedin.com/in/thilinaadias/" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <span class="sr-only">LinkedIn</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
