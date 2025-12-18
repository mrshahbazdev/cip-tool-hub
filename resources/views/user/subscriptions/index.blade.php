<x-app-layout>
    <x-slot name="header" >
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        {{ __('My Subscriptions') }}
                    </h2>
                    <span class="text-sm text-gray-600 dark:text-gray-400 bg-indigo-100 dark:bg-indigo-900/30 px-4 py-2 rounded-lg font-semibold border border-indigo-200 dark:border-indigo-800">
                        {{ $subscriptions->total() }} {{ Str::plural('Subscription', $subscriptions->total()) }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse($subscriptions as $subscription)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-2xl sm:rounded-2xl mb-6 transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                            <!-- Tool Logo & Info -->
                            <div class="flex items-start gap-4 flex-1">
                                <!-- Logo -->
                                <div class="shrink-0">
                                    @if($subscription->package->tool->logo)
                                        <img src="{{ Storage::url($subscription->package->tool->logo) }}" 
                                             alt="{{ $subscription->package->tool->name }}" 
                                             class="h-16 w-16 rounded-xl object-cover shadow-md group-hover:scale-110 transition-transform duration-300 border-2 border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="h-16 w-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $subscription->package->tool->name }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 font-medium mb-3">
                                        {{ $subscription->package->name }}
                                    </p>

                                    <!-- Details -->
                                    <div class="flex flex-wrap items-center gap-4">
                                        <!-- Subdomain -->
                                        @if($subscription->subdomain)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-2 rounded-lg">
                                                <svg class="h-4 w-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                </svg>
                                                <span class="font-medium">{{ $subscription->subdomain }}.{{ $subscription->package->tool->domain }}</span>
                                            </div>
                                        @endif

                                        <!-- Started Date -->
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Started {{ $subscription->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Actions -->
                            <div class="flex flex-col items-end gap-3 md:min-w-[200px]">
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm
                                    @if($subscription->status === 'active') 
                                        bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-2 border-green-200 dark:border-green-700
                                    @elseif($subscription->status === 'expired') 
                                        bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-2 border-red-200 dark:border-red-700
                                    @elseif($subscription->status === 'cancelled') 
                                        bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-2 border-gray-200 dark:border-gray-600
                                    @else 
                                        bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-2 border-yellow-200 dark:border-yellow-700
                                    @endif">
                                    @if($subscription->status === 'active')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($subscription->status === 'expired')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ ucfirst($subscription->status) }}
                                </span>
                                
                                <!-- Expiry Info -->
                                @if($subscription->status === 'active')
                                    <div class="text-right bg-indigo-50 dark:bg-indigo-900/20 px-4 py-2 rounded-lg border border-indigo-200 dark:border-indigo-800">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-1">Expires on</p>
                                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $subscription->expires_at->format('M d, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                            ({{ $subscription->expires_at->diffForHumans() }})
                                        </p>
                                    </div>
                                @elseif($subscription->status === 'expired')
                                    <div class="text-right bg-red-50 dark:bg-red-900/20 px-4 py-2 rounded-lg border border-red-200 dark:border-red-800">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-1">Expired on</p>
                                        <p class="text-sm font-bold text-red-600 dark:text-red-400">
                                            {{ $subscription->expires_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2 w-full">
                                    @if($subscription->status === 'active')
                                        <button class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                                            Manage
                                        </button>
                                    @elseif($subscription->status === 'expired')
                                        <button class="flex-1 px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                                            Renew
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-2xl p-16 text-center border border-gray-200 dark:border-gray-700">
                    <div class="max-w-md mx-auto">
                        <div class="mb-6 inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-full">
                            <svg class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            No Subscriptions Yet
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                            You haven't subscribed to any tools yet. Explore our collection and find the perfect tools for your needs!
                        </p>
                        <a href="{{ route('tools.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Browse Tools
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($subscriptions->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
