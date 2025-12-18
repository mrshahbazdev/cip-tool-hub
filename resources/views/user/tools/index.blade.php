<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                        {{ __('Explore Ecosystem') }}
                    </h2>
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">Premium Development Utilities</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center px-4 py-2 bg-blue-50 border border-blue-100 rounded-xl">
                <span class="text-sm font-black text-blue-700">
                    {{ $tools->total() }} Available {{ Str::plural('Tool', $tools->total()) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Search & Filter Section -->
            <div class="mb-12">
                <div class="bg-white/70 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-blue-500/5 p-8 border border-white">
                    <form method="GET" action="{{ route('tools.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500 transition-colors group-focus-within:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="What are you building today? Search tools..." 
                                class="w-full pl-14 pr-6 py-4 bg-gray-50/50 border-2 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-300 outline-none font-medium shadow-inner"
                            >
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black rounded-2xl hover:shadow-xl hover:shadow-blue-500/20 transform hover:-translate-y-0.5 transition duration-200">
                                Find Tool
                            </button>
                            @if(request('search'))
                                <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center px-6 py-4 bg-white border-2 border-gray-100 text-gray-400 rounded-2xl hover:text-red-500 hover:border-red-100 transition duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </form>
                    
                    @if(request('search'))
                        <div class="mt-6 flex items-center p-4 bg-blue-50 rounded-2xl border border-blue-100/50">
                            <span class="text-sm text-blue-800 font-bold">
                                Filtering ecosystem for: <span class="bg-white px-2 py-1 rounded-md ml-1 shadow-sm">"{{ request('search') }}"</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($tools as $tool)
                    <div class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-[0_20px_50px_rgba(37,99,235,0.12)] border border-gray-100 overflow-hidden transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Card Header / Logo Area -->
                        <div class="p-10 pb-0 relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                            
                            <div class="relative z-10 flex items-center justify-between mb-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100/50 text-blue-600 rounded-3xl flex items-center justify-center shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                                    @if($tool->logo)
                                        <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-12 w-12 object-contain group-hover:brightness-0 group-hover:invert transition-all">
                                    @else
                                        <span class="text-3xl font-black">{{ strtoupper(substr($tool->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.15em] border border-blue-100">
                                    Tool v2.0
                                </div>
                            </div>
                        </div>

                        <!-- Info Body -->
                        <div class="p-10 pt-0">
                            <h3 class="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors mb-3 tracking-tight">
                                {{ $tool->name }}
                            </h3>
                            <div class="flex items-center text-xs font-bold text-gray-400 mb-6 font-mono uppercase tracking-tighter">
                                <i class="fas fa-globe mr-2 opacity-50"></i>{{ $tool->domain }}
                            </div>
                            
                            <p class="text-gray-500 font-medium leading-relaxed mb-8 line-clamp-2 h-12">
                                {{ $tool->description ?? 'Empowering your digital operations with automated scaling and professional management.' }}
                            </p>

                            <!-- Pricing Badge -->
                            <div class="mb-8 p-5 bg-gray-50 rounded-2xl border border-gray-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Price Start</span>
                                    <span class="text-2xl font-black text-gray-900">
                                        @if($tool->packages->count() > 0)
                                            €{{ number_format($tool->packages->min('price'), 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none mb-1">Tier Level</span>
                                    <span class="text-sm font-bold text-blue-600">{{ $tool->packages->count() }} {{ Str::plural('Plan', $tool->packages->count()) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('tools.show', $tool) }}" 
                               class="flex items-center justify-center w-full py-4.5 bg-gray-900 text-white rounded-2xl font-black group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all shadow-lg hover:shadow-blue-500/20">
                                Launch Utility <i class="fas fa-arrow-right ml-3 text-sm transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="bg-white/70 backdrop-blur-xl rounded-[3rem] p-20 text-center border-2 border-dashed border-blue-100">
                            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8">
                                <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">
                                {{ request('search') ? 'No results found' : 'Workspace Empty' }}
                            </h3>
                            <p class="text-lg text-gray-500 max-w-md mx-auto font-medium leading-relaxed">
                                {{ request('search') 
                                    ? "We couldn't find any tools matching \"".request('search')."\". Please check your spelling or try broader terms." 
                                    : "There are currently no tools available in the ecosystem. Our engineers are working on deployment!" }}
                            </p>
                            @if(request('search'))
                                <a href="{{ route('tools.index') }}" class="mt-10 inline-flex items-center px-10 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-xl">
                                    View All Tools
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tools->hasPages())
                <div class="mt-16 flex justify-center">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-4 border border-blue-50">
                        {{ $tools->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>