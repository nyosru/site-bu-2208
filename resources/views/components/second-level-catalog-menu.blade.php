@if($catalogs->isNotEmpty())
    <nav class="second-catalog-menu" aria-label="Каталоги второго уровня">
        @foreach($catalogs as $catalog)
            <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}"
               class="second-catalog-link {{ (int)$activeCatalogId === (int)$catalog->id ? 'is-active' : '' }}">
                {{ $catalog->name }}
            </a>
        @endforeach
    </nav>
@endif
