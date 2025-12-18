<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tools.index') }}" class="group flex items-center justify-center w-10 h-10 bg-white border border-blue-100 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ $tool->name }}
                    </h2>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1">Utility Specifications & Licensing</p>
                </div>
            </div>
            
            @if($hasActiveSubscription)
                <div class="flex items-center px-4 py-2 bg-green-50 border border-green-100 rounded-xl">
                    <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                    <span class="text-xs font-black text-green-700 uppercase tracking-wider">Active License</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <!-- Background Decor (Consistent with Ecosystem) -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Tool Overview Card -->
            <div class="bg-white/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-500/5 p-8 md:p-12 border border-white mb-12">
                <div class="flex flex-col md:flex-row gap-10 items-center md:items-start">
                    <!-- Dynamic Logo Container -->
                    <div class="shrink-0">
                        <div class="w-40 h-40 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-white rounded-[2rem] flex items-center justify-center shadow-xl shadow-blue-500/10 relative">
                            @if($tool->logo)
                                <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-24 w-24 object-contain">
                            @else
                                <svg class="h-20 w-20 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            @endif
                            <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center border border-blue-50">
                                <i class="fas fa-check-shield text-blue-600 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tool Meta Info -->
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-4">
                            <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $tool->name }}</h1>
                            <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">Enterprise</span>
                        </div>
                        
                        <p class="text-lg text-gray-500 font-medium leading-relaxed mb-8 max-w-3xl">
                            {{ $tool->description ?? 'This high-performance utility is part of the CIP ecosystem, designed to streamline your development workflow with automated infrastructure and secure data silos.' }}
                        </p>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                            <div class="flex items-center text-sm font-bold text-gray-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                                <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <span class="font-mono">{{ $tool->domain }}</span>
                            </div>
                            <div class="flex items-center text-sm font-bold text-gray-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                                <i class="fas fa-microchip mr-2 text-indigo-600"></i>
                                <span>API v2.4 Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Select Your Licensing Tier</h2>
                <p class="text-gray-500 font-medium">Choose a plan tailored to your operational scale.</p>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @forelse($tool->packages as $package)
                    @php
                        $isLifetime = $package->duration_type === 'lifetime';
                        $isTrial = $package->duration_type === 'trial';
                    @endphp
                    
                    <div class="group relative bg-white rounded-[2.5rem] shadow-lg hover:shadow-2xl transition-all duration-500 border-2 flex flex-col 
                        {{ $isLifetime ? 'border-indigo-600 ring-4 ring-indigo-500/5' : 'border-transparent hover:border-blue-100' }}">
                        
                        @if($isLifetime)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black px-6 py-1.5 rounded-full uppercase tracking-widest shadow-xl">
                                Recommended
                            </div>
                        @endif

                        <div class="p-10 flex-1">
                            <!-- Package Type Icon -->
                            <div class="mb-6 flex justify-between items-start">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors
                                    {{ $isTrial ? 'bg-orange-50 text-orange-600' : ($isLifetime ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600') }}">
                                    @if($isTrial) <i class="fas fa-hourglass-start text-xl"></i>
                                    @elseif($isLifetime) <i class="fas fa-infinity text-xl"></i>
                                    @else <i class="fas fa-layer-group text-xl"></i>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-blue-600 transition-colors">
                                    {{ $package->duration_type }}
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $package->name }}</h3>
                            
                            <div class="mb-8">
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-black text-gray-900 tracking-tighter">€{{ number_format($package->price, 2) }}</span>
                                    <span class="ml-2 text-gray-400 font-bold text-sm italic">
                                        @if($isLifetime) / forever
                                        @elseif($isTrial) / limit
                                        @else / {{ $package->duration_value }} {{ $package->duration_type }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Features List -->
                            @if($package->features)
                                <ul class="space-y-4 mb-10">
                                    @foreach($package->features as $feature)
                                        <li class="flex items-start group/item">
                                            <div class="shrink-0 w-5 h-5 rounded-full bg-green-50 flex items-center justify-center mr-3 mt-0.5 group-hover/item:bg-green-500 transition-colors">
                                                <svg class="h-3 w-3 text-green-600 group-hover/item:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-600 group-hover/item:text-gray-900 transition-colors leading-tight">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Action Area -->
                        <div class="px-10 pb-10 mt-auto">
                            @auth
                                <a href="{{ route('user.subscriptions.checkout', $package) }}" 
                                   class="flex items-center justify-center w-full py-4.5 rounded-2xl font-black text-lg transition-all shadow-xl hover:-translate-y-1
                                   {{ $isLifetime ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-blue-500/20 hover:shadow-indigo-500/40' : 'bg-gray-900 text-white hover:bg-blue-600 shadow-gray-500/10' }}">
                                    Unlock Now <i class="fas fa-arrow-right ml-3 text-xs"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="flex items-center justify-center w-full py-4.5 bg-gray-100 text-gray-600 rounded-2xl font-black text-lg hover:bg-blue-600 hover:text-white transition-all">
                                    Login to Purchase
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-white/70 backdrop-blur-md rounded-[3rem] p-16 border-2 border-dashed border-blue-100">
                            <i class="fas fa-box-open text-blue-200 text-5xl mb-6"></i>
                            <h3 class="text-2xl font-black text-gray-400">No active plans available for this utility.</h3>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Footer Help Notice -->
            <div class="bg-gray-900 rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <h4 class="text-white font-black text-xl mb-2">Need a custom enterprise solution?</h4>
                    <p class="text-gray-400 font-medium">Contact our architecture team for specialized deployments and SLA guarantees.</p>
                </div>
                <a href="mailto:sales@cip-tools.com" class="px-8 py-4 bg-white text-gray-900 rounded-xl font-black hover:bg-blue-50 transition shadow-lg shrink-0">
                    Contact Architecture
                </a>
            </div>
        </div>
    </div>
</x-app-layout>