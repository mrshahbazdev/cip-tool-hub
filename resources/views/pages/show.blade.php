<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ $page->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl rounded-[2.5rem] border border-white p-10 md:p-16 shadow-xl prose prose-blue max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</x-app-layout>