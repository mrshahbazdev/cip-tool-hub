<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-600/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20l-7-7 7-7" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        {{ __('Insights & Updates') }}
                    </h2>
                    <p class="text-[10px] font-black text-blue-700 uppercase tracking-[0.2em] mt-1.5">Our Latest Stories and Technical Articles</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Category Filter Bar -->
            <div class="mb-12 flex flex-wrap items-center gap-3">
                <a href="{{ route('blog.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-sm font-black transition-all {{ !request('category') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-white text-gray-700 border-2 border-blue-100 hover:bg-blue-50 hover:border-blue-200' }}">
                    All Posts
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-black transition-all {{ request('category') == $category->slug ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-white text-gray-700 border-2 border-blue-100 hover:bg-blue-50 hover:border-blue-200' }}">
                        {{ $category->name }}
                        <span class="ml-1 opacity-60 text-xs font-bold">({{ $category->posts_count }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($posts as $post)
                        <article class="group bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-blue-100 shadow-md hover:shadow-2xl hover:shadow-blue-600/10 transition-all duration-500 flex flex-col h-full overflow-hidden">
                            <!-- Image Container -->
                            <div class="relative aspect-[16/10] overflow-hidden border-b border-blue-50 bg-gray-100">
                                @if($post->cover_image)
                                    <img src="{{ Storage::url($post->cover_image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                         onerror="this.parentElement.querySelector('.image-error-placeholder').classList.remove('hidden'); this.style.display='none';">
                                @endif
                                
                                <!-- Placeholder (Hidden by default, shown if image fails or is null) -->
                                <div class="image-error-placeholder w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center {{ $post->cover_image ? 'hidden' : '' }}">
                                    <svg class="w-16 h-16 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <!-- Category Badge Overlay -->
                                <div class="absolute top-6 left-6">
                                    <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg border border-blue-500">
                                        {{ $post->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Body -->
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center text-[11px] font-black text-blue-700 uppercase tracking-widest mb-4">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    {{ $post->published_at->format('M d, Y') }}
                                    <span class="mx-2 text-blue-200">|</span>
                                    <i class="far fa-user mr-2 text-indigo-600"></i>
                                    {{ $post->user->name }}
                                </div>

                                <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-blue-700 transition-colors leading-tight">
                                    <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                                </h3>

                                <p class="text-gray-600 font-medium leading-relaxed mb-8 line-clamp-3">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                <div class="mt-auto pt-6 border-t border-blue-50">
                                    <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center font-black text-sm text-gray-900 group-hover:text-blue-600 transition-all">
                                        Read Article
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl border border-blue-100 shadow-xl">
                        {{ $posts->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white/80 backdrop-blur-xl rounded-[3rem] p-20 text-center border-2 border-dashed border-blue-200 shadow-sm">
                    <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-feather-alt text-4xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">No Articles Found</h3>
                    <p class="text-gray-600 font-medium max-w-md mx-auto leading-relaxed">We're currently drafting new content. Check back shortly for deep dives into our tools and developer updates.</p>
                    <a href="{{ route('dashboard') }}" class="mt-10 inline-flex items-center px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-blue-600 transition-all shadow-lg">
                        Return to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>