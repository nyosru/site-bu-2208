@if(!empty($items))
    <nav class="w-full pt-3" aria-label="Хлебные крошки каталога">
        <div class="flex items-center gap-2 overflow-x-auto whitespace-nowrap text-sm text-gray-500">
            @foreach($items as $item)
                <span class="inline-flex shrink-0 items-center" wire:key="catalog-breadcrumb-{{ $item['id'] }}">
                    @if($loop->last)
                        <span class="font-semibold text-gray-500">{{ $item['name'] }}</span>
                    @else
                        <a href="{{ route('catalog.show', ['id' => $item['id']]) }}" class="text-gray-500 no-underline hover:no-underline">
                            {{ $item['name'] }}
                        </a>
                    @endif
                    @if(!$loop->last)
                        <span class="ml-2 text-gray-500">/</span>
                    @endif
                </span>
            @endforeach
        </div>
    </nav>
@endif
