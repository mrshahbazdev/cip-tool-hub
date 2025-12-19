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
<body class="antialiased h-full bg-gradient-to-br from-slate-50 via-white to-blue-50 text-gray-900 selection:bg-blue-100 selection:text-blue-700" 
      x-data="{ mobileMenuOpen: false, scrolled: false }" 
      @scroll.window="scrolled = window.pageYOffset > 50">

    @php
        $settings = \App\Models\Setting::first();
        $footerPages = \App\Models\Page::where('is_visible', true)
            ->orderBy('sort_order')
            ->get();
        $tools = \App\Models\Tool::where('status', true)->with('packages')->latest()->take(6)->get();
    @endphp

    <!-- Premium Navigation -->
    <nav class="fixed w-full top-0 z-50 transition-all duration-300 border-b"
         :class="scrolled ? 'glass border-blue-100 shadow-lg py-2' : 'bg-transparent border-transparent py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo Only (Name Removed) -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    @if($settings?->site_logo)
                        <img src="{{ Storage::url($settings->site_logo) }}" 
                             alt="Logo" 
                             class="h-12 w-auto object-contain transform group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition duration-300">
                            <span class="text-white font-extrabold text-xl">CT</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#tools" class="px-5 py-2 text-sm font-black text-gray-600 hover:text-blue-600 transition uppercase tracking-widest">Tools</a>
                    <a href="#features" class="px-5 py-2 text-sm font-black text-gray-600 hover:text-blue-600 transition uppercase tracking-widest">Features</a>
                    <a href="#pricing" class="px-5 py-2 text-sm font-black text-gray-600 hover:text-blue-600 transition uppercase tracking-widest">Pricing</a>
                    
                    <div class="h-6 w-px bg-gray-200 mx-4"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-8 py-4 bg-gray-900 text-white rounded-[1.25rem] font-black text-sm shadow-xl shadow-gray-900/10 hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-tachometer-alt mr-2 text-blue-400"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-black text-gray-600 hover:text-blue-600 transition uppercase tracking-widest">Login</a>
                        <a href="{{ route('register') }}" 
                           class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-[1.25rem] font-black text-sm shadow-xl shadow-blue-500/20 hover:-translate-y-0.5 transition-all duration-300 ml-2">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-3 rounded-2xl bg-white shadow-sm border border-gray-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
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
                <a href="{{ route('dashboard') }}" class="block py-5 text-center bg-gray-900 text-white rounded-2xl font-black">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block text-center font-bold text-gray-600">Login</a>
                <a href="{{ route('register') }}" class="block py-5 text-center bg-blue-600 text-white rounded-2xl font-black">Get Started</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[600px] h-[600px] bg-blue-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-60 animate-blob pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[600px] h-[600px] bg-indigo-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-60 animate-blob animation-delay-2000 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center px-5 py-2.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-10 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-3 animate-pulse"></span>
                Professional SaaS Ecosystem
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black text-gray-900 mb-8 leading-[1.05] tracking-tight">
                Scale Your <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Digital Logic</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-500 max-w-3xl mx-auto mb-14 font-medium leading-relaxed">
                {{ $settings->site_description ?? 'Unlock professional-grade tools with instant subdomain deployment. Secure, scalable, and built for modern enterprise demands.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                @guest
                    <a href="{{ route('register') }}" 
                       class="px-12 py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:shadow-2xl hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-gray-900/10">
                        Get Started For Free
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="px-12 py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:shadow-2xl hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-gray-900/10">
                        <i class="fas fa-tachometer-alt mr-3 text-blue-400"></i>Dashboard
                    </a>
                @endguest
                <a href="#tools" 
                   class="px-12 py-5 glass border-2 border-slate-100 text-slate-700 rounded-[1.5rem] font-black text-lg hover:bg-white transition shadow-lg">
                    Browse Toolset
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mt-28">
                <div class="p-10 bg-white rounded-[2.5rem] border border-white shadow-sm hover:shadow-xl transition-all duration-500">
                    <p class="text-4xl font-black text-blue-600 mb-1">1k+</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Clients</p>
                </div>
                <div class="p-10 bg-white rounded-[2.5rem] border border-white shadow-sm hover:shadow-xl transition-all duration-500">
                    <p class="text-4xl font-black text-indigo-600 mb-1">99.9%</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Uptime Rate</p>
                </div>
                <div class="p-10 bg-white rounded-[2.5rem] border border-white shadow-sm hover:shadow-xl transition-all duration-500">
                    <p class="text-4xl font-black text-purple-600 mb-1">24/7</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Support Live</p>
                </div>
                <div class="p-10 bg-white rounded-[2.5rem] border border-white shadow-sm hover:shadow-xl transition-all duration-500">
                    <p class="text-4xl font-black text-blue-600 mb-1">Instant</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Setup Time</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Solutions Section -->
    <section id="tools" class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black text-gray-900 mb-6 tracking-tight">Available Solutions</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto font-medium leading-relaxed">
                    Deploy world-class applications on your custom subdomains in seconds.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($tools as $tool)
                    <div class="group bg-white rounded-[3rem] border border-slate-100 p-2 shadow-sm hover:shadow-[0_20px_60px_rgba(37,99,235,0.12)] transition-all duration-500 overflow-hidden">
                        <div class="p-10">
                            <div class="flex items-center mb-10">
                                <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-blue-50 text-blue-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white transition-all duration-500">
                                    @if($tool->logo)
                                        <img src="{{ Storage::url($tool->logo) }}" class="h-8 w-8 object-contain group-hover:brightness-0 group-hover:invert transition-all" alt="">
                                    @else
                                        <span class="text-2xl font-black">{{ strtoupper(substr($tool->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="ml-5">
                                    <h3 class="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $tool->name }}</h3>
                                    <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">.{{ $tool->domain }}</span>
                                </div>
                            </div>
                            
                            <p class="text-gray-500 mb-10 font-medium leading-relaxed line-clamp-3 h-20">{{ $tool->description }}</p>
                            
                            @if($tool->packages->count() > 0)
                                @php $cheapest = $tool->packages->sortBy('price')->first(); @endphp
                                <div class="mb-10 p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Entry Tier</p>
                                    <div class="flex items-baseline">
                                        <span class="text-3xl font-black text-gray-900">
                                            {{ $cheapest->price == 0 ? 'FREE' : '€' . number_format($cheapest->price, 2) }}
                                        </span>
                                        <span class="ml-1 text-sm font-bold text-gray-400 italic">/mo</span>
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('tools.show', $tool) }}" 
                               class="flex items-center justify-center w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300 shadow-xl hover:shadow-blue-500/30">
                                View Packages <i class="fas fa-arrow-right ml-3 text-sm"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <h3 class="text-2xl font-black text-slate-400">Workspace Preparation Underway</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Pricing (Static Overview) -->
    <section id="pricing" class="py-32 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black text-gray-900 mb-6 tracking-tight">Transparent Growth</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto font-medium">Predictable scaling for every stage of your project.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div class="bg-white p-12 rounded-[3rem] border border-white shadow-xl flex flex-col h-full">
                    <h3 class="text-xl font-black text-gray-900 mb-2">Starter</h3>
                    <div class="flex items-baseline mb-10">
                        <span class="text-5xl font-black text-gray-900">€0</span>
                        <span class="ml-2 text-gray-400 font-bold italic">/mo</span>
                    </div>
                    <ul class="space-y-5 mb-12 flex-1">
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> 1 Active Tool</li>
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> Up to 10 Users</li>
                        <li class="flex items-center text-gray-500 font-bold"><i class="fas fa-check text-blue-500 mr-3"></i> Community Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-5 bg-slate-100 text-gray-900 rounded-[1.5rem] font-black text-lg hover:bg-blue-600 hover:text-white transition-all duration-300">Get Started</a>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-12 rounded-[3rem] shadow-2xl shadow-blue-500/30 transform scale-105 relative flex flex-col h-full">
                    <div class="absolute -top-5 right-10 px-6 py-2 bg-yellow-400 text-gray-900 text-[10px] font-black rounded-full shadow-lg tracking-widest">MOST POPULAR</div>
                    <h3 class="text-xl font-black text-white mb-2">Professional</h3>
                    <div class="flex items-baseline mb-10">
                        <span class="text-5xl font-black text-white">€49</span>
                        <span class="ml-2 text-blue-100 font-bold italic">/mo</span>
                    </div>
                    <ul class="space-y-5 mb-12 text-white flex-1">
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> 3 Active Tools</li>
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> Unlimited Users</li>
                        <li class="flex items-center font-bold"><i class="fas fa-check text-yellow-300 mr-3"></i> Priority Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-5 bg-white text-blue-600 rounded-[1.5rem] font-black text-lg shadow-2xl hover:bg-blue-50 transition-all duration-300">Select Plan</a>
                </div>

                <div class="bg-white p-12 rounded-[3rem] border border-white shadow-xl flex flex-col h-full">
                    <h3 class="text-xl font-black text-gray-900 mb-2">Enterprise</h3>
                    <div class="flex items-baseline mb-10">
                        <span class="text-5xl font-black text-gray-900 leading-tight">Custom</span>
                    </div>
                    <ul class="space-y-5 mb-12 flex-1 text-gray-500 font-bold">
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> Unlimited Tools</li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> Dedicated SLA</li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-3"></i> Multi-Tenant Architecture</li>
                    </ul>
                    <a href="mailto:sales@cip-tools.com" class="block text-center py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-blue-600 transition-all duration-300 shadow-xl">Contact Architecture</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fully Dynamic Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 relative z-10">
        <div class="max-w-7xl mx-auto pt-24 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <!-- Dynamic Branding -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-5 mb-10">
                        @if($settings?->site_logo)
                            <!-- Wrapped dark logo in a light container for visibility on dark background -->
                            <div class="bg-white p-2.5 rounded-xl shadow-lg border border-gray-700">
                                <img src="{{ Storage::url($settings->site_logo) }}" alt="Logo" class="h-20 w-auto">
                            </div>
                        @else
                            <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <span class="text-white font-black text-2xl">CT</span>
                            </div>
                        @endif
                        <h3 class="text-3xl font-black text-white tracking-tight">
                            {{ $settings->site_name ?? config('app.name', 'CIP Tools') }}
                        </h3>
                    </div>
                    
                    <div class="text-gray-400 mb-10 leading-relaxed max-w-sm text-lg prose prose-invert prose-sm">
                        @if($settings?->footer_description)
                            {!! $settings->footer_description !!}
                        @else
                            {{ $settings->site_description ?? 'Professional SaaS platform delivering high-performance tools for modern business logic and secure data management.' }}
                        @endif
                    </div>

                    <!-- Dynamic Socials -->
                    <div class="flex space-x-6">
                        @if($settings?->facebook_url)
                            <a href="{{ $settings->facebook_url }}" target="_blank" class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if($settings?->twitter_url)
                            <a href="{{ $settings->twitter_url }}" target="_blank" class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-700 hover:text-white transition-all transform hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.055-4.425 5.055H.316l5.733-6.57L0 .75h5.063l3.495 4.62L12.6.75zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633z"/></svg>
                            </a>
                        @endif
                        @if($settings?->linkedin_url)
                            <a href="{{ $settings->linkedin_url }}" target="_blank" class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400 hover:bg-blue-700 hover:text-white transition-all transform hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Platform Section -->
                <div>
                    <h4 class="text-white font-black uppercase text-[10px] tracking-[0.3em] mb-10">Ecosystem</h4>
                    <ul class="space-y-5 text-gray-400 font-bold text-sm">
                        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-500 transition-all flex items-center group"><span class="w-1 h-1 bg-blue-600 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-all"></span>Dashboard</a></li>
                        <li><a href="{{ route('tools.index') }}" class="hover:text-blue-500 transition-all flex items-center group"><span class="w-1 h-1 bg-blue-600 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-all"></span>Browse Tools</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-blue-500 transition-all flex items-center group"><span class="w-1 h-1 bg-blue-600 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-all"></span>Technical Blog</a></li>
                    </ul>
                </div>

                <!-- Dynamic Support Section -->
                <div>
                    <h4 class="text-white font-black uppercase text-[10px] tracking-[0.3em] mb-10">Support & Legal</h4>
                    <ul class="space-y-5 text-gray-400 font-bold text-sm">
                        <li><a href="#" class="hover:text-blue-500 transition-all flex items-center group"><span class="w-1 h-1 bg-blue-600 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-all"></span>Help Center</a></li>
                        @foreach($footerPages as $fPage)
                            <li>
                                <a href="{{ route('pages.show', $fPage->slug) }}" class="hover:text-indigo-500 transition-all flex items-center group">
                                    <span class="w-1 h-1 bg-indigo-600 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-all"></span>
                                    {{ $fPage->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-12 flex flex-col md:flex-row justify-between items-center gap-8">
                <p class="text-gray-500 font-bold text-sm">
                    © {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. Built with Excellence.
                </p>
            </div>
        </div>
        <div class="h-1 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>