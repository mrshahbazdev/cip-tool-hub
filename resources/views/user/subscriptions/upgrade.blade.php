<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('user.subscriptions.index') }}" class="group flex items-center justify-center w-10 h-10 bg-white border border-blue-100 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ __('Upgrade License') }}
                    </h2>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1">Scale your capabilities for {{ $tool->name }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Current Plan Context -->
            <div class="mb-12 bg-white/50 backdrop-blur-xl rounded-[2.5rem] p-8 border border-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Current Instance</p>
                        <h3 class="text-2xl font-black text-gray-900 leading-none">
                            {{ $subscription->package->name }}
                        </h3>
                        <p class="text-sm font-medium text-gray-500 mt-1">
                            Running on <span class="font-mono text-blue-700 font-bold">{{ $subscription->subdomain }}.{{ $tool->domain }}</span>
                        </p>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-100 rounded-xl border border-gray-200">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest block mb-1 text-center">Renewal Date</span>
                    <span class="text-sm font-bold text-gray-700">
                        {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : 'Lifetime Access' }}
                    </span>
                </div>
            </div>

            <!-- Upgrade Options Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Available Scaling Tiers</h2>
                <p class="text-gray-500 font-medium">Select a premium plan to unlock higher limits and enterprise features instantly.</p>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($packages as $package)
                    @php
                        $isLifetime = $package->duration_type === 'lifetime';
                    @endphp
                    
                    <div class="group relative bg-white rounded-[2.5rem] shadow-lg hover:shadow-2xl transition-all duration-500 border-2 flex flex-col border-transparent hover:border-blue-100">
                        
                        @if($isLifetime)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black px-6 py-1.5 rounded-full uppercase tracking-widest shadow-xl">
                                Elite Choice
                            </div>
                        @endif

                        <div class="p-10 flex-1">
                            <div class="mb-6 flex justify-between items-start">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                    @if($isLifetime)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12c0-2.5 2-4.5 4.5-4.5s4.5 2 4.5 4.5-2 4.5-4.5 4.5-4.5-2-4.5-4.5zm10.5 0c0-2.5 2-4.5 4.5-4.5s4.5 2 4.5 4.5-2 4.5-4.5 4.5-4.5-2-4.5-4.5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2z" class="opacity-0"/> <!-- Simple Infinity visualization -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" class="hidden" />
                                            <path d="M16.5 10c2.485 0 4.5 2.015 4.5 4.5s-2.015 4.5-4.5 4.5c-1.47 0-2.77-.707-3.582-1.794L7.5 11.794C6.688 10.707 5.388 10 3.918 10 1.433 10-.582 12.015-.582 14.5S1.433 19 3.918 19c1.47 0 2.77-.707 3.582-1.794L16.5 10z" stroke="currentColor" stroke-width="2" fill="none" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {{ $package->duration_type }}
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $package->name }}</h3>
                            
                            <div class="mb-8">
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-black text-gray-900 tracking-tighter">€{{ number_format($package->price, 2) }}</span>
                                    <span class="ml-2 text-gray-400 font-bold text-sm italic">
                                        / {{ $package->duration_value }} {{ $package->duration_type }}
                                    </span>
                                </div>
                            </div>

                            @if($package->features)
                                <ul class="space-y-4 mb-10">
                                    @foreach($package->features as $feature)
                                        <li class="flex items-start">
                                            <div class="shrink-0 w-5 h-5 rounded-full bg-green-50 flex items-center justify-center mr-3 mt-0.5 group-hover:bg-green-500 transition-colors">
                                                <svg class="h-3 w-3 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors leading-tight">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="px-10 pb-10 mt-auto">
                            <a href="{{ route('user.subscriptions.checkout', $package) }}" 
                               class="flex items-center justify-center w-full py-4.5 rounded-2xl font-black text-lg transition-all shadow-xl bg-gray-900 text-white hover:bg-blue-600 hover:-translate-y-1 shadow-gray-500/10 hover:shadow-blue-500/30">
                                Confirm Upgrade 
                                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-white/70 backdrop-blur-md rounded-[3rem] p-16 border-2 border-dashed border-blue-100">
                            <svg class="w-16 h-16 text-blue-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-2xl font-black text-gray-400">No other plans available for upgrade at this time.</h3>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pro-Tip / Help -->
            <div class="mt-16 bg-indigo-950 rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                    <div class="w-16 h-16 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-white font-black text-xl mb-1">Smooth Transition Guarantee</h4>
                        <p class="text-indigo-200/70 font-medium text-sm leading-relaxed">
                            Upgrading your plan will not disrupt your current service. Your subdomain, data, and settings will remain intact while your new limits and features are applied instantly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>