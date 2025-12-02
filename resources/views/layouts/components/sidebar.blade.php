<aside class="w-64 bg-white shadow-md fixed h-full z-10">
    <div class="p-6 border-b">
        <h1 class="text-xl font-bold text-gray-800">{{ $title ?? config('app.name', 'Laravel') }}</h1>
    </div>
    
    <nav class="mt-6">
        {{ $slot }}
    </nav>
</aside>