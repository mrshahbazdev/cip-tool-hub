<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CIP Tools') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            /* Brand Gradient Animation */
            @keyframes gradientMove {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradientMove 15s ease infinite;
            }
            
            /* Blob Animation - Matching Welcome Page */
            @keyframes blob {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            
            .animate-blob {
                animation: blob 7s infinite;
            }
            
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
            
            /* Smooth Scrolling */
            html { scroll-behavior: smooth; }
            
            /* Custom Scrollbar - Matching Brand Colors */
            ::-webkit-scrollbar {
                width: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: #f8fafc;
            }
            
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(to bottom, #2563eb, #4f46e5);
                border-radius: 10px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(to bottom, #1d4ed8, #4338ca);
            }

            /* Glassmorphism utility */
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        </style>
    </head>
    <body class="font-sans antialiased h-full bg-gradient-to-br from-blue-50 via-white to-indigo-50 text-gray-900 selection:bg-blue-100 selection:text-blue-700">
        <x-banner />

        <div class="min-h-screen flex flex-col relative overflow-hidden">
            <!-- Decorative Background Blobs (Low Opacity for Subtlety) -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000 pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-purple-50 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 pointer-events-none"></div>

            <!-- Navigation (Sticky Glass Effect) -->
            <nav class="glass border-b border-blue-100/50 sticky top-0 z-50 shadow-sm">
                @livewire('navigation-menu')
            </nav>

            <!-- Page Header (Refined UI) -->
            
            <!-- Page Content -->
            <main class="flex-1 relative z-10">
                <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <!-- Premium Footer -->
            @php
                $settings = \App\Models\Setting::first();
                $footerPages = \App\Models\Page::where('is_visible', true)
                    ->orderBy('sort_order')
                    ->get();
            @endphp

            <footer class="bg-gray-900 border-t border-gray-800 w-full">
                <div class="max-w-7xl mx-auto pt-16 pb-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                        <!-- Branding Section -->
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center space-x-3 mb-6">
                                @if($settings?->site_logo)
                                    <img src="{{ Storage::url($settings->site_logo) }}" alt="Logo" class="h-10 w-auto rounded-lg shadow-lg">
                                @else
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2.5 rounded-xl shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                @endif
                                <h3 class="text-2xl font-bold text-white tracking-tight">
                                    {{ $settings->site_name ?? config('app.name', 'CIP Tools') }}
                                </h3>
                            </div>
                            
                            <div class="text-gray-400 mb-8 leading-relaxed max-w-sm text-lg prose prose-invert prose-sm">
                                @if($settings?->footer_description)
                                    {!! $settings->footer_description !!}
                                @else
                                    {{ $settings->site_description ?? 'Your all-in-one platform for powerful development tools and utilities.' }}
                                @endif
                            </div>

                            <!-- Social Links from Settings -->
                            <div class="flex space-x-5">
                                @if($settings?->facebook_url)
                                    <a href="{{ $settings->facebook_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                
                                @if($settings?->twitter_url)
                                    <a href="{{ $settings->twitter_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-700 hover:text-white transition-all transform hover:-translate-y-1">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif

                                @if($settings?->linkedin_url)
                                    <a href="{{ $settings->linkedin_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:bg-blue-700 hover:text-white transition-all transform hover:-translate-y-1">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Platform Links -->
                        <div>
                            <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Platform</h4>
                            <ul class="space-y-4">
                                <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Dashboard</a></li>
                                <li><a href="{{ route('tools.index') }}" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Browse Tools</a></li>
                                <li><a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-blue-500 transition-colors flex items-center group"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>Technical Blog</a></li>
                            </ul>
                        </div>

                        <!-- Support & Dynamic Pages -->
                        <div>
                            <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Support & Legal</h4>
                            <ul class="space-y-4">
                                <li>
                                    <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors flex items-center group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                        Help Center
                                    </a>
                                </li>

                                @foreach($footerPages as $fPage)
                                    <li>
                                        <a href="{{ route('pages.show', $fPage->slug) }}" class="text-gray-400 hover:text-indigo-500 transition-colors flex items-center group">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                            {{ $fPage->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500 font-medium">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>&copy; {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. All rights reserved.</span>
                        </div>
                    </div>
                </div>

                <!-- Animated Accent Line -->
                <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 animate-gradient"></div>
            </footer>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Scroll to Top (Modern Style) -->
        <button id="scrollToTop" class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl shadow-2xl hover:shadow-blue-500/50 transform hover:scale-110 transition-all duration-300 opacity-0 pointer-events-none z-50 flex items-center justify-center border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <script>
            // Scroll to Top Logic
            const scrollToTopBtn = document.getElementById('scrollToTop');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.replace('opacity-0', 'opacity-100');
                    scrollToTopBtn.classList.remove('pointer-events-none');
                } else {
                    scrollToTopBtn.classList.replace('opacity-100', 'opacity-0');
                    scrollToTopBtn.classList.add('pointer-events-none');
                }
            });

            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        </script>
    </body>
</html>