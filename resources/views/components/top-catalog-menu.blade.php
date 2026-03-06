<nav class="top-catalog-menu" aria-label="Каталоги первого уровня">
    @foreach($catalogs as $catalog)
        <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}"
           class="top-catalog-link {{ (int)$activeTopCatalogId === (int)$catalog->id ? 'is-active' : '' }}">
            {{ $catalog->name }}
        </a>
    @endforeach
</nav>
