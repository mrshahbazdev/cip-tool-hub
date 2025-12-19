@php
    $settings = \App\Models\Setting::first();
@endphp

<nav x-data="{ open: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-blue-100/50 dark:border-gray-800 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo Only (Text Removed to match Welcome Page) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        @if($settings?->site_logo)
                            <img src="{{ Storage::url($settings->site_logo) }}" 
                                 alt="Logo" 
                                 class="h-12 w-auto object-contain transform group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transform group-hover:scale-105 transition duration-300">
                                <span class="text-white font-extrabold text-xl">CT</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-black rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/admin" 
                       class="inline-flex items-center px-4 py-2 text-sm font-black rounded-xl text-indigo-600 hover:bg-indigo-50 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Admin Backend
                    </a>
                    @endif
                    
                    <a href="{{ route('tools.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-black rounded-xl transition-all duration-200 {{ request()->routeIs('tools.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Browse Tools
                    </a>
                    
                    <a href="{{ route('user.subscriptions.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-black rounded-xl transition-all duration-200 {{ request()->routeIs('user.subscriptions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        Subscriptions
                    </a>

                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-black rounded-xl transition-all duration-200 {{ request()->routeIs('blog.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20l-7-7 7-7" />
                        </svg>
                        Blog
                    </a>
                </div>
            </div>

            <!-- Right Side Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex items-center text-sm border-2 border-blue-100 dark:border-gray-700 rounded-2xl hover:border-blue-400 transition duration-200 overflow-hidden shadow-sm">
                                        <img class="size-10 rounded-xl object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-700 text-sm font-black rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 shadow-sm">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest bg-gray-50 dark:bg-gray-800/50">
                                    {{ __('Account Settings') }}
                                </div>
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-100 dark:border-gray-800"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-red-600 font-bold">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-black text-gray-600 dark:text-gray-300 hover:text-blue-600 transition px-4 py-2 uppercase tracking-widest">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 text-sm font-black bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 transition duration-300">Join Free</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-2xl text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition duration-200">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-blue-50 dark:border-gray-800 bg-white/95 backdrop-blur-md">
        <div class="pt-4 pb-4 space-y-1 px-3">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="rounded-xl font-bold py-3">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('tools.index') }}" :active="request()->routeIs('tools.*')" class="rounded-xl font-bold py-3">Tools</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('user.subscriptions.index') }}" :active="request()->routeIs('user.subscriptions.*')" class="rounded-xl font-bold py-3">Subscriptions</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('blog.index') }}" :active="request()->routeIs('blog.*')" class="rounded-xl font-bold py-3">Blog</x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-4 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center px-6 mb-4">
                    <img class="size-12 rounded-xl object-cover shadow-sm border border-gray-100" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    <div class="ms-4">
                        <div class="font-black text-lg text-gray-900 dark:text-white leading-none">{{ Auth::user()->name }}</div>
                        <div class="font-bold text-sm text-gray-500 leading-none mt-1">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1 px-3">
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="rounded-xl font-bold">Settings</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" class="w-full text-left font-black text-red-600 px-4 py-3 rounded-xl hover:bg-red-50 transition-colors uppercase tracking-widest text-xs">Sign Out</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>