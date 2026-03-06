@extends('layouts.app')

@section('content')
    <section class="board-intro">
        <h1>{{ $catalog['name'] }}</h1>
        <p>Объявления текущего каталога и всех вложенных</p>

        @if(!empty($children))
            <div class="catalog-children">
                @foreach($children as $child)
                    <a href="{{ route('catalog.show', ['id' => $child['id']]) }}" class="catalog-child-link">
                        {{ $child['name'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="ads-section">
        <div class="ads-total">Найдено объявлений: {{ $advertisements->total() }}</div>

        @if($advertisements->isEmpty())
            <div class="ads-empty">В этом каталоге объявлений пока нет.</div>
        @else
            <div class="ads-list">
                @foreach($advertisements as $advertisement)
                    <article class="ad-card">
                        <div class="ad-photo-wrap">
                            @php($photo = $advertisement->photos->first())
                            @if($photo)
                                <img src="{{ $photo->path }}" alt="{{ $advertisement->title }}" class="ad-photo">
                            @else
                                <div class="ad-photo ad-photo-empty">Нет фото</div>
                            @endif
                        </div>

                        <div class="ad-content">
                            <h3>{{ $advertisement->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($advertisement->description, 190) }}</p>
                            <div class="ad-meta">
                                <span>Автор: {{ $advertisement->user?->name ?? $advertisement->user?->email ?? 'Не указан' }}</span>
                                <span>ID: {{ $advertisement->id }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="ads-pagination">
                {{ $advertisements->links() }}
            </div>
        @endif
    </section>
@endsection
