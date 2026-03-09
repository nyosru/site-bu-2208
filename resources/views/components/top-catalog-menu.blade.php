<nav class="flex gap-2 overflow-x-auto pb-0.5" aria-label="Каталоги первого уровня">
    @foreach($catalogs as $catalog)
        <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}"
           class="rounded-full border px-3 py-1.5 text-sm font-semibold whitespace-nowrap no-underline {{ (int)$activeTopCatalogId === (int)$catalog->id ? 'border-blue-700 bg-blue-100 text-blue-800' : 'border-blue-200 bg-blue-50 text-blue-700' }}">
            {{ $catalog->name }}
        </a>
    @endforeach
</nav>
