<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Tools') }}
        </h2>
    </x-slot>
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search & Filter Section -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form method="GET" action="{{ route('tools.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search tools..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('tools.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                Clear
                            </a>
                        @endif
                    </form>
                    
                    @if(request('search'))
                        <div class="mt-4 text-sm text-gray-600">
                            Showing results for: <strong>{{ request('search') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-gray-600">
                Found {{ $tools->total() }} {{ Str::plural('tool', $tools->total()) }}
            </div>

            <!-- Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tools as $tool)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
                        <!-- Tool Logo -->
                        <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            @if($tool->logo)
                                <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-20 w-20 mx-auto rounded-lg object-cover">
                            @else
                                <div class="h-20 w-20 mx-auto bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Tool Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $tool->name }}</h3>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $tool->description ?? 'No description available' }}
                            </p>

                            <!-- Domain -->
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                {{ $tool->domain }}
                            </div>

                            <!-- Packages Info -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-600">
                                    {{ $tool->packages->count() }} {{ Str::plural('package', $tool->packages->count()) }} available
                                </span>
                                @if($tool->packages->count() > 0)
                                    <span class="text-lg font-bold text-green-600">
                                        From €{{ number_format($tool->packages->min('price'), 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- View Details Button -->
                            <a href="{{ route('tools.show', $tool) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                                @if(request('search'))
                                    No tools found for "{{ request('search') }}"
                                @else
                                    No tools available
                                @endif
                            </h3>
                            <p class="text-gray-500">
                                @if(request('search'))
                                    Try a different search term
                                @else
                                    Check back later for new tools!
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tools->hasPages())
                <div class="mt-8">
                    {{ $tools->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
