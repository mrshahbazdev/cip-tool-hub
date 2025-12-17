<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subscription Successful
        </h2>
    </x-slot>
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                <!-- Success Icon -->
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 animate-bounce">
                        <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <h3 class="text-3xl font-bold text-gray-900 mb-2">
                    @if($subscription->package->price == 0)
                        Free Subscription Activated!
                    @else
                        Payment Successful!
                    @endif
                </h3>
                <p class="text-gray-600 mb-8">
                    Your subscription has been successfully 
                    @if($subscription->status === 'active')
                        activated
                    @else
                        created and is pending activation
                    @endif
                </p>

                <!-- Subscription Details Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 mb-8 text-left shadow-inner">
                    <h4 class="text-xl font-bold text-gray-900 mb-6 text-center">Subscription Details</h4>
                    
                    <div class="space-y-4">
                        <!-- Tool -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span class="text-gray-600">Tool:</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $subscription->package->tool->name }}</span>
                        </div>

                        <!-- Package -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-gray-600">Package:</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $subscription->package->name }}</span>
                        </div>

                        <!-- Subdomain -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <span class="text-gray-600">Your Domain:</span>
                            </div>
                            <a href="http://{{ $subscription->full_domain }}" target="_blank" class="font-mono font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $subscription->full_domain }}
                            </a>
                        </div>

                        <!-- Duration -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600">Duration:</span>
                            </div>
                            <span class="font-semibold text-gray-900">
                                @if($subscription->package->duration_type === 'lifetime')
                                    Lifetime Access
                                @else
                                    {{ $subscription->package->duration_value }} {{ ucfirst($subscription->package->duration_type) }}
                                @endif
                            </span>
                        </div>

                        <!-- Start Date -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600">Started:</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $subscription->starts_at->format('M d, Y') }}</span>
                        </div>

                        <!-- Expiry Date -->
                        @if($subscription->expires_at)
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-600">Expires:</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $subscription->expires_at->format('M d, Y') }}</span>
                            </div>
                        @endif

                        <!-- Price -->
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-400 to-green-600 rounded-lg shadow-md text-white">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Amount Paid:</span>
                            </div>
                            <span class="text-2xl font-bold">€{{ number_format($subscription->package->price, 2) }}</span>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600">Status:</span>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($subscription->status === 'active') bg-green-100 text-green-800
                                @elseif($subscription->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Features List -->
                @if($subscription->package->features)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 text-left">
                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Your Package Includes:
                        </h4>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($subscription->package->features as $feature)
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8 text-left">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">What's Next?</h4>
                            <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                                <li>Check your email for subscription confirmation and login details</li>
                                <li>Access your subdomain at <a href="http://{{ $subscription->full_domain }}" class="underline font-semibold">{{ $subscription->full_domain }}</a></li>
                                @if($subscription->status === 'pending')
                                    <li>Your subscription will be activated once payment is confirmed (2-3 business days)</li>
                                @endif
                                <li>Visit "My Subscriptions" to manage your subscription</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Transaction ID -->
                @if($subscription->transaction)
                    <div class="mb-8">
                        <p class="text-sm text-gray-500">
                            Transaction ID: <span class="font-mono font-semibold text-gray-700">{{ $subscription->transaction->transaction_id }}</span>
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="http://{{ $subscription->full_domain }}" target="_blank" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transform hover:scale-105 transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Access Your Service
                    </a>

                    <a href="{{ route('user.subscriptions.index') }}" class="inline-flex items-center justify-center px-8 py-3 border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 font-semibold transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        View All Subscriptions
                    </a>

                    <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Browse More Tools
                    </a>
                </div>

                <!-- Thank You Message -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-gray-600">
                        Thank you for choosing us! If you have any questions, please don't hesitate to 
                        <a href="#" class="text-blue-600 hover:underline font-semibold">contact our support team</a>.
                    </p>
                </div>
            </div>

            <!-- Confetti Animation (Optional) -->
            <div class="text-center mt-6">
                <p class="text-6xl animate-pulse">🎉</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-redirect after 30 seconds (optional)
        // setTimeout(function() {
        //     window.location.href = '{{ route("user.subscriptions.index") }}';
        // }, 30000);
    </script>
    @endpush
</x-app-layout>
