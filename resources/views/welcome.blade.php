<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-indigo-600">🚀 SaaS Platform</h1>
                    <div class="space-x-4">
                        @auth
                            <span class="text-gray-700">Hi, {{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h2 class="text-5xl font-bold mb-4">Choose Your Perfect Tool</h2>
                <p class="text-xl">Get your own subdomain in seconds! 🎉</p>
            </div>
        </div>

        <!-- Tools Grid -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h3 class="text-3xl font-bold mb-8">Available Tools</h3>
            
            @if($tools->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-yellow-700">⚠️ No tools available yet. Please run setup.</p>
                    <p class="text-sm text-yellow-600 mt-2">Run: php artisan tinker and create a tool</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($tools as $tool)
                        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-indigo-600">{{ substr($tool->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold">{{ $tool->name }}</h4>
                                    <span class="text-sm text-gray-500 font-mono">.{{ $tool->domain }}</span>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-4">{{ $tool->description }}</p>
                            
                            @if($tool->packages->count() > 0)
                                <p class="text-2xl font-bold text-indigo-600 mb-4">
                                    {{ $tool->packages->first()->price == 0 ? 'FREE' : '€' . number_format($tool->packages->first()->price, 2) }}
                                </p>
                            @endif

                            <a href="{{ route('tools.show', $tool) }}" 
                               class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                View Packages →
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
