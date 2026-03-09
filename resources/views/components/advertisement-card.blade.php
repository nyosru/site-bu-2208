@props(['advertisement'])

<article class="overflow-hidden rounded-xl border border-slate-200
    bg-white
    ">

    <a href="{{ route('advertisements.show', ['advertisement' => $advertisement->id]) }}"
       wire:navigate >
    <div class="aspect-[16/9] w-full bg-slate-100">
        @php($photo = $advertisement->photos->first())
        @if($photo)
            <img
                src="{{ $photo->path }}"
                alt="{{ $advertisement->title }}"
                class="h-full w-full object-cover"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-slate-500">Нет фото</div>
        @endif
    </div>
    </a>

    <div class="space-y-3 p-4">
        <h3 class="text-base font-semibold text-slate-900">
            <a href="{{ route('advertisements.show', ['advertisement' => $advertisement->id]) }}"
               wire:navigate
               class="text-slate-900 no-underline hover:underline">
                {{ $advertisement->title }}
            </a>
        </h3>
        {{--        <p class="text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($advertisement->description, 190) }}</p>--}}
        <div class="grid grid-cols-1 gap-1 text-xs text-slate-600
{{--        sm:grid-cols-2--}}
        ">
            <span>Тип: {{ ($advertisement->ad_type ?? $advertisement->type) === 'buy' ? 'Ищу для покупки' : 'Продаю' }}</span>
            @if( !empty($advertisement->price) )
                <span>Цена: {{ number_format((float)$advertisement->price, 2, '.', ' ') }}</span>
            @endif
            {{--            <span>Автор: {{ $advertisement->user?->name ?? $advertisement->user?->email ?? 'Не указан' }}</span>--}}
            {{--            <span>ID: {{ $advertisement->id }}</span>--}}
        </div>

    </div>
</article>
