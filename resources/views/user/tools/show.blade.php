<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tool->name }}
            </h2>
            <a href="{{ route('tools.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Tools
            </a>
        </div>
    </x-slot>
        <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tool Header -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            @if($tool->logo)
                                <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-32 w-32 rounded-lg object-cover">
                            @else
                                <div class="h-32 w-32 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $tool->name }}</h1>
                            
                            <p class="text-gray-600 mb-4">
                                {{ $tool->description ?? 'No description available for this tool.' }}
                            </p>

                            <div class="flex items-center gap-6">
                                <div class="flex items-center text-gray-600">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    <a href="https://{{ $tool->domain }}" target="_blank" class="hover:text-blue-600">
                                        {{ $tool->domain }}
                                    </a>
                                </div>

                                @if($hasActiveSubscription)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active Subscription
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Choose Your Plan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($tool->packages as $package)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-all duration-300 {{ $package->duration_type === 'lifetime' ? 'ring-2 ring-yellow-400' : '' }}">
                            @if($package->duration_type === 'lifetime')
                                <div class="bg-yellow-400 text-yellow-900 text-center py-1 text-sm font-semibold">
                                    BEST VALUE
                                </div>
                            @endif

                            <div class="p-6">
                                <!-- Package Name -->
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                                
                                <!-- Price -->
                                <div class="mb-4">
                                    <span class="text-4xl font-bold text-gray-900">€{{ number_format($package->price, 2) }}</span>
                                    @if($package->duration_type !== 'lifetime')
                                        <span class="text-gray-500">/ {{ $package->duration_value }} {{ $package->duration_type }}</span>
                                    @else
                                        <span class="text-gray-500">/ one-time</span>
                                    @endif
                                </div>

                                <!-- Duration Badge -->
                                <div class="mb-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($package->duration_type === 'trial') bg-orange-100 text-orange-800
                                        @elseif($package->duration_type === 'lifetime') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        @if($package->duration_type === 'lifetime')
                                            Lifetime Access
                                        @elseif($package->duration_type === 'trial')
                                            {{ $package->duration_value }} Days Trial
                                        @else
                                            {{ $package->duration_value }} {{ ucfirst($package->duration_type) }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Features -->
                                @if($package->features)
                                    <ul class="space-y-3 mb-6">
                                        @foreach($package->features as $feature)
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-700">{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <!-- Subscribe Button -->
                                @auth
                                    <a href="{{ route('user.subscriptions.checkout', $package) }}" class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-semibold">
                                        Subscribe Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-semibold">
                                        Login to Subscribe
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500">No packages available for this tool yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
