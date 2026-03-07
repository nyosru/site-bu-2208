@if($catalogs->isNotEmpty())
    <nav class="second-catalog-menu flex flex-wrap" aria-label="Каталоги второго уровня">
        @foreach($catalogs as $catalog)
            <div>
            <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}"
               class="second-catalog-link {{ (int)$activeCatalogId === (int)$catalog->id ? 'is-active' : '' }}">
                {{ $catalog->name }}
            </a>
            </div>
        @endforeach
    </nav>
@endif
