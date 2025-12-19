<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ __('User Workspace') }}
                    </h2>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mt-1.5">Command Center & Resource Overview</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center px-5 py-2.5 bg-blue-50 border border-blue-100 rounded-2xl shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-3 animate-pulse"></span>
                <span class="text-xs font-black text-blue-700 uppercase tracking-widest">System Online</span>
            </div>
        </div>
    </x-slot>

    <div class="py-16 bg-slate-50/50 relative overflow-hidden min-h-screen">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Welcome Hero -->
            <div class="bg-white rounded-[3rem] shadow-[0_15px_50px_rgba(0,0,0,0.04)] p-10 md:p-16 border border-white mb-16 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none"></div>
                
                <div class="flex flex-col lg:flex-row gap-12 items-center lg:items-start relative z-10">
                    <div class="shrink-0">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-blue-500/20">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight mb-4">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ Auth::user()->name }}</span>
                        </h1>
                        <p class="text-lg text-gray-500 font-medium leading-relaxed mb-10 max-w-2xl">
                            Ready to accelerate your workflow? Manage your active tool licenses, track performance, and deploy new utilities from your dashboard.
                        </p>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                            <a href="{{ route('tools.index') }}" class="px-10 py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-blue-600 transition-all shadow-xl hover:-translate-y-1">
                                Deploy New Tool
                            </a>
                            <a href="{{ route('user.subscriptions.index') }}" class="px-10 py-5 bg-white border-2 border-slate-100 text-slate-700 rounded-[1.5rem] font-black text-lg hover:bg-slate-50 transition-all shadow-sm">
                                View Subscriptions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                @php
                    $activeCount = Auth::user()->subscriptions()->where('status', 'active')->count();
                    $totalCount = Auth::user()->subscriptions()->count();
                @endphp
                
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <p class="text-4xl font-black text-gray-900 mb-1">{{ $activeCount }}</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Licenses</p>
                </div>

                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <p class="text-4xl font-black text-gray-900 mb-1">{{ $totalCount }}</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Deployments</p>
                </div>

                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-4xl font-black text-gray-900 mb-1">99.9%</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Uptime</p>
                </div>

                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                    </div>
                    <p class="text-4xl font-black text-gray-900 mb-1">Pro</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Account Tier</p>
                </div>
            </div>

            <!-- Quick Access Section -->
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-black text-gray-900 flex items-center">
                    <span class="w-10 h-1.5 bg-blue-600 mr-4 rounded-full"></span>
                    Ready-to-Launch Utilities
                </h3>
                <a href="{{ route('user.subscriptions.index') }}" class="text-sm font-black text-blue-600 hover:text-indigo-700 uppercase tracking-widest transition-colors flex items-center">
                    Manage All
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @php
                    $latestSubs = Auth::user()->subscriptions()->with('package.tool')->where('status', 'active')->latest()->take(3)->get();
                @endphp

                @forelse($latestSubs as $sub)
                    <div class="group bg-white rounded-[2.5rem] shadow-sm border border-white p-8 hover:shadow-2xl transition-all duration-500 flex flex-col">
                        <div class="flex items-center mb-8">
                            <div class="w-14 h-14 bg-slate-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                @if($sub->package->tool->logo)
                                    <img src="{{ Storage::url($sub->package->tool->logo) }}" class="h-8 w-8 object-contain group-hover:brightness-0 group-hover:invert" alt="">
                                @else
                                    <span class="text-xl font-black">{{ strtoupper(substr($sub->package->tool->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h4 class="font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $sub->package->tool->name }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $sub->package->name }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4 mb-8 border border-slate-100 flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            <span class="text-xs font-mono text-slate-600 truncate">{{ $sub->subdomain }}.{{ $sub->package->tool->domain }}</span>
                        </div>

                        <a href="https://{{ $sub->subdomain }}.{{ $sub->package->tool->domain }}" target="_blank" class="w-full py-4 bg-gray-900 text-white rounded-xl font-black text-center group-hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/20">
                            Launch Instance
                        </a>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200">
                            <svg class="w-16 h-16 text-slate-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h4 class="text-2xl font-black text-slate-400 mb-2">No Active Licenses</h4>
                            <p class="text-slate-400 font-medium mb-8">Deploy your first enterprise tool to populate your workspace.</p>
                            <a href="{{ route('tools.index') }}" class="inline-flex items-center px-10 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-xl">
                                Explore Toolset
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>