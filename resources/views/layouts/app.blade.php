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