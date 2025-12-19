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
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mt-1">Premium Development Utilities</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-xl">
                <span class="text-sm font-black text-indigo-700">
                    {{ $tools->total() }} Available {{ Str::plural('Utility', $tools->total()) }}
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Main Content Area with Slate background for contrast -->
    <div class="py-16 bg-slate-50/50 relative overflow-hidden min-h-screen">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Search & Filter Section (Distinct Card) -->
            <div class="mb-16">
                <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-10 border border-blue-50/50">
                    <form method="GET" action="{{ route('tools.index') }}" class="flex flex-col lg:flex-row gap-6">
                        <div class="flex-1 relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-blue-500 transition-colors group-focus-within:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="What are you building today? Search tools..." 
                                class="w-full pl-16 pr-8 py-5 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:bg-white focus:ring-8 focus:ring-blue-500/5 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition-all duration-300 outline-none font-semibold shadow-inner"
                            >
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center px-12 py-5 bg-gray-900 text-white font-black rounded-[1.5rem] hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/20 transform hover:-translate-y-1 transition-all duration-300">
                                Find Utility
                            </button>
                            @if(request('search'))
                                <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center px-6 py-5 bg-white border-2 border-red-50 text-red-400 rounded-[1.5rem] hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($tools as $tool)
                    <div class="group bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_60px_rgba(37,99,235,0.12)] border border-white overflow-hidden transition-all duration-500 transform hover:-translate-y-3">
                        <!-- Card Visual Accent -->
                        <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="p-10">
                            <!-- Logo Area -->
                            <div class="flex items-center justify-between mb-10">
                                <div class="w-20 h-20 bg-gradient-to-br from-slate-50 to-blue-50 border border-slate-100 rounded-3xl flex items-center justify-center shadow-inner group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-500">
                                    @if($tool->logo)
                                        <img src="{{ Storage::url($tool->logo) }}" alt="{{ $tool->name }}" class="h-10 w-10 object-contain group-hover:brightness-0 group-hover:invert transition-all duration-500">
                                    @else
                                        <span class="text-3xl font-black text-blue-600 group-hover:text-white transition-colors duration-500">{{ strtoupper(substr($tool->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="px-4 py-2 bg-blue-50/50 rounded-xl border border-blue-100">
                                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none">v2.4 Ready</span>
                                </div>
                            </div>

                            <!-- Info Body -->
                            <h3 class="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors mb-2 tracking-tight">
                                {{ $tool->name }}
                            </h3>
                            <div class="flex items-center text-xs font-bold text-gray-400 mb-6 font-mono tracking-tighter">
                                <svg class="w-3 h-3 mr-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                {{ $tool->domain }}
                            </div>
                            
                            <p class="text-gray-500 font-medium leading-relaxed mb-10 line-clamp-3 h-20">
                                {{ $tool->description ?? 'Launch this high-performance utility on your custom subdomain with automated provisioning and secure architecture.' }}
                            </p>

                            <!-- Pricing Badge (Premium Card) -->
                            <div class="mb-10 p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Starting at</span>
                                        <span class="text-3xl font-black text-gray-900">
                                            @if($tool->packages->count() > 0)
                                                €{{ number_format($tool->packages->min('price'), 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex -space-x-2 justify-end mb-2">
                                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">P</div>
                                            <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-600">S</div>
                                        </div>
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ $tool->packages->count() }} Scaling {{ Str::plural('Tier', $tool->packages->count()) }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('tools.show', $tool) }}" 
                               class="flex items-center justify-center w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300 shadow-xl hover:shadow-blue-500/30">
                                Launch Utility 
                                <svg class="w-5 h-5 ml-3 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="bg-white rounded-[3rem] p-24 text-center border-2 border-dashed border-slate-200">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                                <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">
                                {{ request('search') ? 'No utilities found' : 'Ecosystem Empty' }}
                            </h3>
                            <p class="text-lg text-gray-500 max-w-md mx-auto font-medium leading-relaxed mb-12">
                                {{ request('search') 
                                    ? "We couldn't locate any tools matching \"".request('search')."\". Please refine your search term." 
                                    : "Our engineers are currently provisioning new tools. Check back shortly for our latest deployments." }}
                            </p>
                            @if(request('search'))
                                <a href="{{ route('tools.index') }}" class="inline-flex items-center px-12 py-5 bg-blue-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-2xl shadow-blue-500/20">
                                    Refresh Toolset
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Premium Pagination -->
            @if($tools->hasPages())
                <div class="mt-20 flex justify-center">
                    <div class="bg-white rounded-3xl shadow-xl p-4 border border-slate-100">
                        {{ $tools->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>