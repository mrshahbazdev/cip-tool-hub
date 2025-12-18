<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ __('Active Portfolio') }}
                    </h2>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1">Managed Assets & Tool Licenses</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-xl">
                <span class="text-sm font-black text-indigo-700">
                    {{ $subscriptions->total() }} Licensed {{ Str::plural('Utility', $subscriptions->total()) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <!-- Background Decor (Consistent with Ecosystem) -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @forelse($subscriptions as $subscription)
                <div class="bg-white/70 backdrop-blur-xl rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-white mb-8 group overflow-hidden">
                    <div class="p-8 md:p-10">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                            <!-- Tool Logo & Primary Info -->
                            <div class="flex items-center gap-6 flex-1">
                                <!-- Enhanced Logo Container -->
                                <div class="shrink-0 relative">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100/50 rounded-3xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-500">
                                        @if($subscription->package->tool->logo)
                                            <img src="{{ Storage::url($subscription->package->tool->logo) }}" 
                                                 alt="{{ $subscription->package->tool->name }}" 
                                                 class="h-12 w-12 object-contain group-hover:rotate-3 transition-transform">
                                        @else
                                            <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-lg shadow-md flex items-center justify-center border border-blue-50">
                                        <i class="fas fa-certificate text-blue-600 text-[8px]"></i>
                                    </div>
                                </div>

                                <!-- Tool Details -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors tracking-tight">
                                            {{ $subscription->package->tool->name }}
                                        </h3>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[9px] font-black rounded-md uppercase tracking-widest">
                                            {{ $subscription->package->name }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6">
                                        <!-- Subdomain Badge -->
                                        @if($subscription->subdomain)
                                            <a href="https://{{ $subscription->subdomain }}.{{ $subscription->package->tool->domain }}" target="_blank" class="flex items-center text-sm font-bold text-blue-600 hover:text-indigo-700 transition-colors bg-blue-50/50 px-3 py-1.5 rounded-xl border border-blue-100">
                                                <i class="fas fa-external-link-alt mr-2 text-[10px] opacity-70"></i>
                                                <span class="font-mono">{{ $subscription->subdomain }}.{{ $subscription->package->tool->domain }}</span>
                                            </a>
                                        @endif

                                        <!-- Meta Info -->
                                        <div class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                                            Started {{ $subscription->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Status & Conversion -->
                            <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between lg:justify-center w-full lg:w-auto gap-4">
                                <!-- Dynamic Status Badge -->
                                <div class="flex flex-col items-end">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-50 text-green-700 border-green-100',
                                            'expired' => 'bg-red-50 text-red-700 border-red-100',
                                            'cancelled' => 'bg-gray-50 text-gray-500 border-gray-100',
                                            'pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        ][$subscription->status] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                    @endphp

                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-[0.15em] border shadow-sm {{ $statusClasses }}">
                                        @if($subscription->status === 'active')
                                            <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2.5 animate-pulse"></span>
                                        @endif
                                        {{ $subscription->status }}
                                    </span>

                                    <!-- Expiry Logic -->
                                    @if($subscription->status === 'active' && $subscription->expires_at)
                                        <p class="mt-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            Ends in <span class="text-blue-600">{{ $subscription->expires_at->diffForHumans(['parts' => 1]) }}</span>
                                        </p>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    @if($subscription->status === 'active')
                                        <a href="#" class="px-6 py-3 bg-gray-900 text-white text-xs font-black rounded-xl hover:bg-blue-600 transition shadow-lg hover:shadow-blue-500/20">
                                            Manage Access
                                        </a>
                                    @else
                                        <a href="{{ route('tools.show', $subscription->package->tool) }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-black rounded-xl hover:shadow-xl transition transform hover:-translate-y-0.5">
                                            Renew License
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Professional Empty State -->
                <div class="py-20 text-center">
                    <div class="bg-white/70 backdrop-blur-xl rounded-[3rem] p-16 border-2 border-dashed border-blue-100 max-w-2xl mx-auto shadow-2xl shadow-blue-500/5">
                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Ecosystem Empty</h3>
                        <p class="text-lg text-gray-500 font-medium leading-relaxed mb-10">
                            You haven't initialized any tool licenses yet. Our marketplace features world-class utilities ready for instant deployment.
                        </p>
                        <a href="{{ route('tools.index') }}" 
                           class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black rounded-2xl hover:shadow-2xl hover:shadow-blue-500/30 transform hover:-translate-y-1 transition duration-300">
                            <i class="fas fa-rocket mr-3"></i>Discover Tools
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Premium Pagination -->
            @if($subscriptions->hasPages())
                <div class="mt-16 flex justify-center">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-4 border border-blue-50">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>