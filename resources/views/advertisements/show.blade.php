@extends('layouts.app')

@section('content')
    <section class="mt-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $advertisement->title }}</h1>

        <div class="flex flax-row space-x-5 ">

            <div class="w-1/2 mt-4 space-y-4">
                @if($advertisement->photos->isNotEmpty())
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($advertisement->photos as $photo)
                            <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                <img
                                    src="{{ $photo->path }}"
                                    alt="{{ $advertisement->title }}"
                                    class="h-56 w-full object-cover"
                                >
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-600">
                        У объявления пока нет фотографий.
                    </div>
                @endif

            </div>
            <div>

                {{--                <div>--}}
                {{--                    <span class="font-semibold">Категория:</span>--}}
                {{--                    <a href="{{ route('catalog.show', ['id' => $advertisement->catalog_id]) }}" class="text-blue-700 no-underline hover:underline">--}}
                {{--                        {{ $advertisement->catalog?->name ?? 'Не указана' }}--}}
                {{--                    </a>--}}
                {{--                </div>--}}
                <div>
                    <span class="font-semibold">Тип:</span>
                    {{ ($advertisement->ad_type ?? $advertisement->type) === 'buy' ? 'Ищу для покупки' : 'Продаю' }}
                </div>
                @if(!empty($advertisement->price))
                    <div>
                        <span class="font-semibold">Цена:</span>
                        {{ number_format((float) $advertisement->price, 2, '.', ' ') }}
                    </div>
                @endif
                <div>
                    <span class="font-semibold">Автор:</span>
                    {{ $advertisement->user?->name ?? $advertisement->user?->email ?? 'Не указан' }}
                </div>
                <div>
                    <span class="font-semibold">Опубликовано:</span>
                    {{ $advertisement->created_at?->format('d.m.Y H:i') }}
                </div>

            </div>
        </div>
        <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-800">
            {!! nl2br(e($advertisement->description)) !!}
        </div>
    </section>
@endsection
