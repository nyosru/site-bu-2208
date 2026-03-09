@if($catalogs->isNotEmpty())
    <nav class="w-full mt-2 flex flex-row flex-wrap gap-2" aria-label="Каталоги второго уровня">
        @foreach($catalogs as $catalog)
            <div>
                <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}"
                   class="rounded-full border px-3 py-1 text-xs font-semibold whitespace-nowrap {{ (int)$activeCatalogId === (int)$catalog->id ? 'border-slate-700 bg-slate-200 text-slate-900' : 'border-slate-300 bg-slate-50 text-slate-700' }}">
                    {{ $catalog->name }}
                </a>
            </div>
        @endforeach
    </nav>
@endif
