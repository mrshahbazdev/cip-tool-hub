<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tools.index') }}" class="group flex items-center justify-center w-12 h-12 bg-white border border-blue-100 rounded-2xl shadow-sm hover:bg-blue-600 hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ $tool->name }}
                    </h2>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mt-1.5">Utility Specifications & Licensing</p>
                </div>
            </div>
            
            @if($hasActiveSubscription)
                <div class="flex items-center px-5 py-2.5 bg-green-50 border border-green-100 rounded-2xl shadow-sm">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-green-500 mr-3 animate-pulse"></span>
                    <span class="text-xs font-black text-green-700 uppercase tracking-widest">Active License</span>
                </div>
            @endif
        </div>
    </x-slot>

    <!-- Main Content Area with Slate background for contrast -->
    <div class="py-16 bg-slate-50/50 relative overflow-hidden min-h-screen">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Tool Overview Card (Enhanced Contrast) -->
            <div class="bg-white rounded-[3rem] shadow-[0_15px_50px_rgba(0,0,0,0.04)] p-10 md:p-16 border border-white mb-16 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none"></div>
                
                <div class="flex flex-col lg:row gap-12 items-center lg:items-start relative z-10">
                    <!-- Dynamic Logo Container -->
                    <div class="shrink-0">
                        <div class="w-48 h-48 bg-gradient-to-br from-slate-50 to-blue-50 border-4 border-white rounded-[2.5rem] flex items-center justify-center shadow-2xl shadow-blue-500/10 relative">
                            @if($tool->logo)
                                <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-28 w-28 object-contain">
                            @else
                                <span class="text-6xl font-black text-blue-600">{{ strtoupper(substr($tool->name, 0, 1)) }}</span>
                            @endif
                            <div class="absolute -bottom-4 -right-4 w-14 h-14 bg-white rounded-2xl shadow-xl flex items-center justify-center border border-blue-50">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tool Meta Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-6">
                            <h1 class="text-5xl font-black text-gray-900 tracking-tight leading-none">{{ $tool->name }}</h1>
                            <span class="px-4 py-1.5 bg-blue-600 text-white text-[11px] font-black rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-blue-600/20">Enterprise</span>
                        </div>
                        
                        <p class="text-xl text-gray-500 font-medium leading-relaxed mb-10 max-w-4xl">
                            {{ $tool->description ?? 'Launch this high-performance utility on your custom subdomain with automated provisioning, military-grade security, and seamless API integration.' }}
                        </p>

                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6">
                            <div class="flex items-center text-sm font-bold text-gray-700 bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100 shadow-inner">
                                <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                <span class="font-mono tracking-tight">{{ $tool->domain }}</span>
                            </div>
                            <div class="flex items-center text-sm font-bold text-gray-700 bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100 shadow-inner">
                                <svg class="h-5 w-5 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                <span>API v2.8 Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-3">Licensing Tiers</h2>
                <p class="text-lg text-gray-500 font-medium">Select a plan tailored to your operational scale and technical requirements.</p>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-20">
                @forelse($tool->packages as $package)
                    @php
                        $isLifetime = $package->duration_type === 'lifetime';
                        $isTrial = $package->duration_type === 'trial';
                    @endphp
                    
                    <div class="group relative bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_25px_70px_rgba(37,99,235,0.15)] transition-all duration-500 border-2 flex flex-col 
                        {{ $isLifetime ? 'border-indigo-600 ring-8 ring-indigo-500/5' : 'border-transparent hover:border-blue-100' }}">
                        
                        @if($isLifetime)
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-[11px] font-black px-8 py-2 rounded-full uppercase tracking-[0.2em] shadow-2xl">
                                Recommended
                            </div>
                        @endif

                        <div class="p-12 flex-1">
                            <!-- Package Type Icon (SVG) -->
                            <div class="mb-10 flex justify-between items-start">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500 shadow-inner
                                    {{ $isTrial ? 'bg-orange-50 text-orange-600' : ($isLifetime ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-blue-50 text-blue-600') }}">
                                    @if($isTrial)
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @elseif($isLifetime)
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    @endif
                                </div>
                                <span class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-blue-600 transition-colors">
                                    {{ $package->duration_type }}
                                </span>
                            </div>

                            <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $package->name }}</h3>
                            
                            <div class="mb-10">
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-black text-gray-900 tracking-tighter">€{{ number_format($package->price, 2) }}</span>
                                    <span class="ml-2 text-slate-400 font-bold text-sm italic">
                                        @if($isLifetime) / forever
                                        @elseif($isTrial) / trial
                                        @else / {{ $package->duration_value }} {{ $package->duration_type }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Features List -->
                            @if($package->features)
                                <ul class="space-y-5 mb-10">
                                    @foreach($package->features as $feature)
                                        <li class="flex items-start">
                                            <div class="shrink-0 w-6 h-6 rounded-full bg-green-50 flex items-center justify-center mr-4 mt-0.5 group-hover:bg-green-500 transition-colors">
                                                <svg class="h-3.5 w-3.5 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-gray-900 transition-colors leading-tight">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Action Area (Taller Buttons) -->
                        <div class="px-12 pb-12 mt-auto">
                            @auth
                                <a href="{{ route('user.subscriptions.checkout', $package) }}" 
                                   class="flex items-center justify-center w-full py-5 rounded-[1.5rem] font-black text-lg transition-all shadow-xl hover:-translate-y-1
                                   {{ $isLifetime ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-blue-500/25 hover:shadow-indigo-500/40' : 'bg-gray-900 text-white hover:bg-blue-600 shadow-gray-900/10' }}">
                                    Unlock Access 
                                    <svg class="w-5 h-5 ml-3 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="flex items-center justify-center w-full py-5 bg-slate-100 text-slate-600 rounded-[1.5rem] font-black text-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    Sign In to License
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div class="bg-white rounded-[3rem] p-20 border-2 border-dashed border-slate-200">
                            <svg class="w-20 h-20 text-slate-200 mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-3xl font-black text-slate-400">No active plans available for this utility.</h3>
                            <p class="text-slate-400 font-medium mt-4">Our engineers are updating the licensing protocols.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Footer Help Notice (Premium Dark Card) -->
            <div class="bg-gray-950 rounded-[3.5rem] p-12 md:p-16 flex flex-col lg:flex-row items-center justify-between gap-10 shadow-2xl shadow-indigo-900/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>
                
                <div class="text-center lg:text-left relative z-10">
                    <h4 class="text-3xl md:text-4xl font-black text-white mb-4 tracking-tight">Need a custom enterprise solution?</h4>
                    <p class="text-xl text-slate-400 font-medium">Contact our specialized architecture team for multi-tenant deployments and dedicated SLA guarantees.</p>
                </div>
                
                <a href="mailto:sales@cip-tools.com" class="px-12 py-5 bg-white text-gray-950 rounded-[1.5rem] font-black text-lg hover:bg-blue-50 transition-all shadow-xl hover:-translate-y-1 shrink-0 relative z-10 flex items-center">
                    Contact Architecture
                    <svg class="w-5 h-5 ml-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>