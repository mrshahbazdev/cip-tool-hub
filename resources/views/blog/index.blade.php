<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20l-7-7 7-7" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ __('Insights & Updates') }}
                    </h2>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1">Our Latest Stories and Technical Articles</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Category Filter Bar -->
            <div class="mb-12 flex flex-wrap items-center gap-3">
                <a href="{{ route('blog.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ !request('category') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white text-gray-600 border border-gray-100 hover:bg-blue-50' }}">
                    All Posts
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request('category') == $category->slug ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white text-gray-600 border border-gray-100 hover:bg-blue-50' }}">
                        {{ $category->name }}
                        <span class="ml-1 opacity-60 text-xs">({{ $category->posts_count }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($posts as $post)
                        <article class="group bg-white/70 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 flex flex-col h-full overflow-hidden">
                            <!-- Image Container -->
                            <div class="relative aspect-[16/10] overflow-hidden">
                                @if($post->cover_image)
                                    <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <!-- Badge -->
                                <div class="absolute top-6 left-6">
                                    <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg">
                                        {{ $post->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Body -->
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                                    <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                                    {{ $post->published_at->format('M d, Y') }}
                                    <span class="mx-2">•</span>
                                    <i class="far fa-user mr-2 text-indigo-500"></i>
                                    {{ $post->user->name }}
                                </div>

                                <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-blue-600 transition-colors leading-tight">
                                    <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                                </h3>

                                <p class="text-gray-500 font-medium leading-relaxed mb-8 line-clamp-3">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                <div class="mt-auto">
                                    <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center font-black text-sm text-gray-900 group-hover:text-blue-600 transition-all">
                                        Read Article
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-20 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white/70 backdrop-blur-xl rounded-[3rem] p-20 text-center border-2 border-dashed border-blue-100">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-feather-alt text-blue-300 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">No Articles Found</h3>
                    <p class="text-gray-500 font-medium max-w-md mx-auto">We're currently drafting new content. Check back shortly for deep dives into our tools and dev updates.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>