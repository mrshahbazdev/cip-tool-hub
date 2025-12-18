<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        {{ __('Browse Tools') }}
                    </h2>
                    <span class="text-sm text-gray-600 dark:text-gray-400 bg-indigo-100 dark:bg-indigo-900/30 px-4 py-2 rounded-lg font-semibold border border-indigo-200 dark:border-indigo-800">
                        {{ $tools->total() }} {{ Str::plural('Tool', $tools->total()) }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search & Filter Section -->
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('tools.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by name, domain, or description..." 
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 outline-none"
                            >
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 dark:bg-gray-600 text-white font-bold rounded-xl hover:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear
                            </a>
                        @endif
                    </form>
                    
                    @if(request('search'))
                        <div class="mt-4 flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 rounded-r-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-indigo-800 dark:text-indigo-300 font-medium">
                                Showing results for: <strong>"{{ request('search') }}"</strong>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tools as $tool)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-2xl sm:rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 dark:border-gray-700 group">
                        <!-- Tool Logo -->
                        <div class="p-8 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            @if($tool->logo)
                                <img src="{{ Storage::url($tool->logo) }}" 
                                     alt="{{ $tool->name }}" 
                                     class="h-24 w-24 mx-auto rounded-2xl object-cover shadow-md group-hover:scale-110 transition-transform duration-300 relative z-10">
                            @else
                                <div class="h-24 w-24 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 relative z-10">
                                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Tool Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $tool->name }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ $tool->description ?? 'No description available' }}
                            </p>

                            <!-- Domain -->
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4 bg-gray-50 dark:bg-gray-700/50 px-3 py-2 rounded-lg">
                                <svg class="h-4 w-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <span class="font-medium">{{ $tool->domain }}</span>
                            </div>

                            <!-- Packages Info -->
                            <div class="flex items-center justify-between mb-5 p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $tool->packages->count() }} {{ Str::plural('package', $tool->packages->count()) }}
                                    </span>
                                </div>
                                @if($tool->packages->count() > 0)
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                        From €{{ number_format($tool->packages->min('price'), 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- View Details Button -->
                            <a href="{{ route('tools.show', $tool) }}" 
                               class="block w-full text-center px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200 group/btn">
                                <span class="flex items-center justify-center">
                                    View Details
                                    <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-2xl p-16 text-center border border-gray-200 dark:border-gray-700">
                            <div class="max-w-md mx-auto">
                                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                                    @if(request('search'))
                                        No tools found
                                    @else
                                        No tools available
                                    @endif
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    @if(request('search'))
                                        We couldn't find any tools matching "<strong>{{ request('search') }}</strong>". Try a different search term or browse all tools.
                                    @else
                                        There are no tools available at the moment. Check back later for new additions!
                                    @endif
                                </p>
                                @if(request('search'))
                                    <a href="{{ route('tools.index') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                        View All Tools
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tools->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                        {{ $tools->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
