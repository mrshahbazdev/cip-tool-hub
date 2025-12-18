<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Subscriptions') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse($subscriptions as $subscription)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <!-- Tool & Package Info -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $subscription->package->tool->name }}</h3>
                                <p class="text-gray-600 mt-1">{{ $subscription->package->name }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    @if($subscription->subdomain)
                                        <span class="text-sm text-gray-500">
                                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                            </svg>
                                            {{ $subscription->subdomain }}.{{ $subscription->package->tool->domain }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Status & Actions -->
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($subscription->status === 'active') bg-green-100 text-green-800
                                    @elseif($subscription->status === 'expired') bg-red-100 text-red-800
                                    @elseif($subscription->status === 'cancelled') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                                
                                @if($subscription->status === 'active')
                                    <span class="text-sm text-gray-600">
                                        Expires: {{ $subscription->expires_at->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12 text-center">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Subscriptions Yet</h3>
                    <p class="text-gray-500 mb-6">You haven't subscribed to any tools yet.</p>
                    <a href="{{ route('tools.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Browse Tools
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($subscriptions->hasPages())
                <div class="mt-8">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
