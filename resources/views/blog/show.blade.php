<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('blog.index') }}" class="w-10 h-10 bg-white border border-blue-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">Article View</h2>
                <nav class="flex text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">
                    <a href="{{ route('blog.index') }}">Blog</a>
                    <span class="mx-2 text-gray-300">/</span>
                    <span class="text-gray-500">{{ $post->category->name }}</span>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header -->
            <div class="text-center mb-16">
                <div class="flex items-center justify-center text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] mb-6">
                    <span class="px-4 py-1 bg-blue-50 rounded-lg">{{ $post->category->name }}</span>
                    <span class="mx-3 text-gray-300">•</span>
                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight leading-[1.1] mb-10">
                    {{ $post->title }}
                </h1>

                <!-- Author Badge -->
                <div class="flex items-center justify-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&color=7F9CF5&background=EBF4FF" class="w-12 h-12 rounded-2xl border-2 border-white shadow-md mr-4">
                    <div class="text-left">
                        <p class="text-sm font-black text-gray-900 leading-none">{{ $post->user->name }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Lead Architect</p>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->cover_image)
                <div class="mb-16 rounded-[3rem] overflow-hidden shadow-2xl shadow-blue-500/10 border-4 border-white">
                    <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
                </div>
            @endif

            <!-- Article Content -->
            <div class="bg-white/70 backdrop-blur-xl rounded-[3rem] border border-white p-10 md:p-16 shadow-sm">
                @if($post->excerpt)
                    <div class="mb-12 p-8 bg-blue-50 rounded-3xl border border-blue-100">
                        <p class="text-xl text-blue-900 font-bold italic leading-relaxed">
                            "{{ $post->excerpt }}"
                        </p>
                    </div>
                @endif

                <div class="prose prose-lg prose-blue max-w-none prose-headings:font-black prose-headings:tracking-tight prose-a:text-blue-600 prose-img:rounded-3xl prose-img:shadow-lg">
                    {!! $post->content !!}
                </div>

                <!-- Footer Tags/Actions -->
                <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-bold text-gray-400">Share:</span>
                        <div class="flex space-x-2">
                            <button class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button onclick="window.print()" class="px-6 py-3 bg-gray-50 text-gray-600 font-black rounded-xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center">
                        <i class="fas fa-print mr-2"></i> Print Article
                    </button>
                </div>
            </div>

            <!-- Related Posts Section -->
            @if($relatedPosts->count() > 0)
                <div class="mt-24">
                    <h3 class="text-2xl font-black text-gray-900 mb-10 flex items-center">
                        <span class="w-8 h-1 bg-blue-600 mr-4 rounded-full"></span>
                        Keep Reading
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($relatedPosts as $rp)
                            <a href="{{ route('blog.show', $rp) }}" class="group block bg-white rounded-3xl p-6 border border-gray-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all">
                                <div class="text-[10px] font-black text-blue-600 uppercase mb-3">{{ $rp->category->name }}</div>
                                <h4 class="text-lg font-black text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">{{ $rp->title }}</h4>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Styles for Prose (Add to head or global CSS if not present) -->
    @push('styles')
        <style>
            .prose h2, .prose h3 { margin-top: 2.5rem; margin-bottom: 1.5rem; }
            .prose p { margin-bottom: 1.5rem; color: #4b5563; line-height: 1.8; }
            .prose blockquote { border-left-color: #2563eb; font-weight: 600; font-style: italic; color: #1e3a8a; }
        </style>
    @endpush
</x-app-layout>