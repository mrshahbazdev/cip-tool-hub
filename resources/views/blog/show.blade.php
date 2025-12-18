<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('blog.index') }}" class="w-10 h-10 bg-white border border-blue-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-400 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">Article View</h2>
                <nav class="flex text-[10px] font-bold text-blue-700 uppercase tracking-widest mt-1">
                    <a href="{{ route('blog.index') }}" class="hover:text-indigo-800 transition-colors">Blog</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-indigo-600 font-black">{{ $post->category->name }}</span>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header -->
            <div class="text-center mb-16">
                <div class="flex items-center justify-center text-[11px] font-black text-blue-700 uppercase tracking-[0.2em] mb-6">
                    <span class="px-4 py-1.5 bg-blue-100/50 border border-blue-200 rounded-lg">{{ $post->category->name }}</span>
                    <span class="mx-3 text-blue-300">|</span>
                    <span class="text-gray-600">{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight leading-[1.1] mb-10">
                    {{ $post->title }}
                </h1>

                <!-- Author Badge -->
                <div class="flex items-center justify-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&color=FFFFFF&background=2563eb" class="w-14 h-14 rounded-2xl border-4 border-white shadow-lg mr-4">
                    <div class="text-left">
                        <p class="text-sm font-black text-gray-900 leading-none">{{ $post->user->name }}</p>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1.5">Lead Architect</p>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->cover_image)
                <div class="mb-16 rounded-[3rem] overflow-hidden shadow-2xl shadow-blue-900/10 border-4 border-white">
                    <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
                </div>
            @endif

            <!-- Article Content -->
            <div class="bg-white/80 backdrop-blur-2xl rounded-[3rem] border border-blue-100 p-10 md:p-16 shadow-xl relative">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-b-full"></div>

                @if($post->excerpt)
                    <div class="mb-12 p-10 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-[2.5rem] border border-blue-100 relative overflow-hidden">
                        <div class="absolute top-4 left-4 text-blue-200 opacity-50">
                            <i class="fas fa-quote-left text-4xl"></i>
                        </div>
                        <p class="text-xl text-blue-950 font-extrabold italic leading-relaxed relative z-10">
                            {{ $post->excerpt }}
                        </p>
                    </div>
                @endif

                <div class="prose prose-lg prose-blue max-w-none prose-headings:text-gray-900 prose-headings:font-black prose-headings:tracking-tight prose-a:text-blue-600 prose-a:font-bold prose-img:rounded-3xl prose-img:shadow-xl prose-strong:text-gray-900 prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50/50 prose-blockquote:py-2 prose-blockquote:rounded-r-2xl">
                    {!! $post->content !!}
                </div>

                <!-- Footer Tags/Actions -->
                <div class="mt-16 pt-10 border-t border-blue-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-black text-gray-500 uppercase tracking-widest">Share Article:</span>
                        <div class="flex space-x-2">
                            <button class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                <i class="fab fa-twitter text-lg"></i>
                            </button>
                            <button class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="window.print()" class="px-8 py-4 bg-gray-900 text-white font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-blue-600 transition-all flex items-center shadow-lg">
                        <i class="fas fa-print mr-3"></i> Print Article
                    </button>
                </div>
            </div>

            <!-- Related Posts Section -->
            @if($relatedPosts->count() > 0)
                <div class="mt-24">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-gray-900 flex items-center">
                            <span class="w-10 h-1.5 bg-blue-600 mr-4 rounded-full"></span>
                            Continue Reading
                        </h3>
                        <div class="h-px flex-1 bg-blue-100 ml-6 hidden sm:block"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($relatedPosts as $rp)
                            <a href="{{ route('blog.show', $rp) }}" class="group block bg-white rounded-[2rem] p-8 border border-blue-50 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-200 transition-all">
                                <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                                    {{ $rp->category->name }}
                                </div>
                                <h4 class="text-lg font-black text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">{{ $rp->title }}</h4>
                                <div class="mt-6 flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                    <i class="far fa-clock mr-1.5"></i> 5 Min Read
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Styles for Prose -->
    @push('styles')
        <style>
            .prose h2, .prose h3 { margin-top: 3rem; margin-bottom: 1.5rem; color: #111827; }
            .prose p { margin-bottom: 1.5rem; color: #374151; line-height: 1.85; }
            .prose blockquote { 
                border-left-width: 4px;
                font-style: italic;
                color: #1e3a8a;
                padding-left: 1.5rem;
                margin: 2rem 0;
            }
            .prose strong { color: #111827; font-weight: 800; }
            .prose ul li::before { background-color: #2563eb !important; }
        </style>
    @endpush
</x-app-layout>