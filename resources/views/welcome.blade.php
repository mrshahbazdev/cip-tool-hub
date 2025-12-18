<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CIP Tools') }} - Professional SaaS Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradientMove 15s ease infinite;
        }

        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased h-full bg-gradient-to-br from-blue-50 via-white to-indigo-50 text-gray-900 selection:bg-blue-100 selection:text-blue-700" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- Premium Navigation -->
    <nav class="fixed w-full top-0 z-50 transition-all duration-300 border-b"
         :class="scrolled ? 'glass border-blue-100 shadow-lg py-2' : 'bg-transparent border-transparent py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition duration-300">
                        <span class="text-white font-extrabold text-xl">CT</span>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-black tracking-tight text-gray-900">CIP<span class="text-blue-600">Tools</span></h1>
                        <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 leading-none">Professional Ecosystem</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#tools" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-blue-600 transition">Tools</a>
                    <a href="#features" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-blue-600 transition">Features</a>
                    <a href="#pricing" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-blue-600 transition">Pricing</a>
                    
                    <div class="h-6 w-px bg-gray-200 mx-4"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-extrabold text-sm shadow-xl shadow-blue-500/20 hover:-translate-y-0.5 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>My Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-blue-600 transition">Login</a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-extrabold text-sm shadow-xl shadow-blue-500/20 hover:-translate-y-0.5 transition ml-2">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-3 rounded-2xl bg-white shadow-sm border border-gray-100">
                    <i class="fas fa-bars text-xl text-blue-600"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden glass border-t border-blue-50 p-6 space-y-4">
            <a href="#tools" @click="mobileMenuOpen = false" class="block text-lg font-bold text-gray-700">Tools</a>
            <a href="#features" @click="mobileMenuOpen = false" class="block text-lg font-bold text-gray-700">Features</a>
            <a href="#pricing" @click="mobileMenuOpen = false" class="block text-lg font-bold text-gray-700">Pricing</a>
            <hr class="border-gray-100">
            @auth
                <a href="{{ route('dashboard') }}" class="block py-4 text-center bg-blue-600 text-white rounded-2xl font-bold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block text-center font-bold text-gray-600">Login</a>
                <a href="{{ route('register') }}" class="block py-4 text-center bg-blue-600 text-white rounded-2xl font-bold">Get Started</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[600px] h-[600px] bg-blue-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-60 animate-blob pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[600px] h-[600px] bg-indigo-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-60 animate-blob animation-delay-2000 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold uppercase tracking-widest mb-8">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
                Next Generation SaaS Architecture
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black text-gray-900 mb-8 leading-[1.1] tracking-tight">
                Empower Your <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Business Growth</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-500 max-w-3xl mx-auto mb-12 font-medium leading-relaxed">
                Unlock professional-grade tools with instant subdomain deployment. 
                Secure, scalable, and built for modern enterprise demands.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                @guest
                    <a href="{{ route('register') }}" 
                       class="px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-lg hover:shadow-2xl hover:shadow-blue-500/40 transform hover:-translate-y-1 transition shadow-xl">
                        <i class="fas fa-rocket mr-3"></i>Start Free Trial
                    </a>
                @else
                    <!-- Fixed: Changed missing tenants route to dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-lg hover:shadow-2xl hover:shadow-blue-500/40 transform hover:-translate-y-1 transition shadow-xl">
                        <i class="fas fa-tachometer-alt mr-3"></i>Go to Dashboard
                    </a>
                @endguest
                <a href="#tools" 
                   class="px-10 py-5 glass border border-blue-100 text-blue-700 rounded-2xl font-black text-lg hover:bg-white transition shadow-lg">
                    Browse Toolset
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mt-24">
                <div class="p-8 bg-white/50 backdrop-blur-sm rounded-[2rem] border border-white">
                    <p class="text-4xl font-black text-blue-600 mb-1">1k+</p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Clients</p>
                </div>
                <div class="p-8 bg-white/50 backdrop-blur-sm rounded-[2rem] border border-white">
                    <p class="text-4xl font-black text-indigo-600 mb-1">99.9%</p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Uptime Rate</p>
                </div>
                <div class="p-8 bg-white/50 backdrop-blur-sm rounded-[2rem] border border-white">
                    <p class="text-4xl font-black text-purple-600 mb-1">24/7</p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Support Live</p>
                </div>
                <div class="p-8 bg-white/50 backdrop-blur-sm rounded-[2rem] border border-white">
                    <p class="text-4xl font-black text-blue-600 mb-1">Instant</p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Setup Time</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Tools Section -->
    <section id="tools" class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">Available Solutions</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto font-medium">
                    Deploy world-class applications on your custom subdomains in seconds.
                </p>
            </div>

            @if($tools->isEmpty())
                <div class="bg-blue-50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-blue-200">
                    <i class="fas fa-layer-group text-blue-300 text-6xl mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Workspace Preparation</h3>
                    <p class="text-gray-500">We are currently curating the best tools for you. Please check back shortly.</p>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-10">
                    @foreach($tools as $tool)
                        <div class="group bg-white rounded-[2.5rem] border border-gray-100 p-2 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
                            <div class="p-8">
                                <div class="flex items-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                        <span class="text-2xl font-black">{{ strtoupper(substr($tool->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $tool->name }}</h3>
                                        <span class="text-xs font-bold text-blue-500 uppercase tracking-tighter">.{{ $tool->domain }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-500 mb-8 font-medium leading-relaxed line-clamp-3">{{ $tool->description }}</p>
                                
                                @if($tool->packages->count() > 0)
                                    @php $cheapest = $tool->packages->sortBy('price')->first(); @endphp
                                    <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Entry Plan</p>
                                        <div class="flex items-baseline">
                                            <span class="text-3xl font-black text-gray-900">
                                                {{ $cheapest->price == 0 ? 'FREE' : '€' . number_format($cheapest->price, 2) }}
                                            </span>
                                            <span class="ml-1 text-sm font-bold text-gray-400">/mo</span>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('tools.show', $tool) }}" 
                                   class="flex items-center justify-center w-full py-4 bg-gray-900 text-white rounded-2xl font-black group-hover:bg-blue-600 transition shadow-lg">
                                    View Packages <i class="fas fa-chevron-right ml-3 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Built for Excellence</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto font-medium">Everything you need to manage a global business infrastructure.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Zero-Touch Ops</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">Automatic provisioning of databases and subdomains. Launch your enterprise tool in under 60 seconds.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-8">
                        <i class="fas fa-shield-halved text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Military-Grade</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">End-to-end encryption for all tenant data. We follow GDPR guidelines to the highest standard.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-8">
                        <i class="fas fa-expand-arrows-alt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Elastic Scaling</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">Start with 10 users and grow to 100,000. Our infrastructure adapts to your load automatically.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Transparent Growth</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto font-medium">Simple plans that scale as you do.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Plan 1 -->
                <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-lg">
                    <h3 class="text-xl font-black text-gray-900 mb-2">Starter</h3>
                    <div class="flex items-baseline mb-8">
                        <span class="text-5xl font-black text-gray-900">€0</span>
                        <span class="ml-2 text-gray-400 font-bold">/mo</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> 1 Active Tool</li>
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> Up to 10 Users</li>
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> Community Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-4 bg-gray-100 text-gray-900 rounded-2xl font-black hover:bg-gray-200 transition">Get Started</a>
                </div>

                <!-- Plan 2 (Featured) -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-10 rounded-[3rem] shadow-2xl shadow-blue-500/30 transform scale-105 relative">
                    <div class="absolute top-0 right-10 -translate-y-1/2 px-4 py-1 bg-yellow-400 text-gray-900 text-xs font-black rounded-full">MOST POPULAR</div>
                    <h3 class="text-xl font-black text-white mb-2">Professional</h3>
                    <div class="flex items-baseline mb-8">
                        <span class="text-5xl font-black text-white">€49</span>
                        <span class="ml-2 text-blue-100 font-bold">/mo</span>
                    </div>
                    <ul class="space-y-4 mb-10 text-white">
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> 3 Active Tools</li>
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> Unlimited Users</li>
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> Priority Support</li>
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> Custom Branding</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-4 bg-white text-blue-600 rounded-2xl font-black shadow-xl hover:bg-blue-50 transition">Select Plan</a>
                </div>

                <!-- Plan 3 -->
                <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-lg">
                    <h3 class="text-xl font-black text-gray-900 mb-2">Enterprise</h3>
                    <div class="flex items-baseline mb-8">
                        <span class="text-5xl font-black text-gray-900">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-10 text-gray-500 font-bold">
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> Unlimited Tools</li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> Dedicated SLA</li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> On-Premise Options</li>
                    </ul>
                    <a href="mailto:sales@cip-tools.com" class="block text-center py-4 bg-gray-900 text-white rounded-2xl font-black hover:bg-black transition">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20 px-4">
        <div class="max-w-5xl mx-auto bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[4rem] p-20 text-center relative overflow-hidden shadow-2xl shadow-blue-500/20">
            <div class="absolute inset-0 bg-white/5 opacity-20 animate-pulse"></div>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-8">Ready to transform your workflow?</h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('register') }}" class="px-10 py-5 bg-white text-blue-600 rounded-2xl font-black text-lg shadow-xl hover:bg-blue-50 transition">Get Started Now</a>
                <a href="mailto:support@cip-tools.com" class="px-10 py-5 border-2 border-white text-white rounded-2xl font-black text-lg hover:bg-white/10 transition">Talk to Us</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 relative z-10">
                <div class="max-w-7xl mx-auto pt-16 pb-8 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                        <!-- Branding -->
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2.5 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white tracking-tight">{{ config('app.name', 'CIP Tools') }}</h3>
                            </div>
                            <p class="text-gray-400 mb-8 leading-relaxed max-w-sm text-lg">
                                Your all-in-one platform for powerful development tools and utilities. Build faster, work smarter.
                            </p>
                            <!-- Social Links -->
                            <div class="flex space-x-5">
                                <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-700 hover:text-white transition-all transform hover:-translate-y-1 shadow-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div>
                            <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Platform</h4>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Dashboard</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Developer Tools</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Documentation</a></li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Support</h4>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Help Center</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Privacy Policy</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500 font-medium">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                        </div>
                    </div>
                </div>
                
                <!-- Animated Accent Line -->
                <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 animate-gradient"></div>
            </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>