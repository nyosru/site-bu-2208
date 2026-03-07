@extends('layouts.app')

@section('content')
    <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $catalog['name'] }}</h1>
        <p class="mt-2 text-sm text-slate-600">Объявления текущего каталога и всех вложенных</p>

        @if(!empty($children))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($children as $child)
                    <a
                        href="{{ route('catalog.show', ['id' => $child['id']]) }}"
                        class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-100"
                    >
                        {{ $child['name'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 text-sm text-slate-600">Найдено объявлений: {{ $advertisements->total() }}</div>

        @if($advertisements->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-600">
                В этом каталоге объявлений пока нет.
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @foreach($advertisements as $advertisement)
                    <x-advertisement-card :advertisement="$advertisement" />
                @endforeach
            </div>

            <div class="mt-5">
                {{ $advertisements->links() }}
            </div>
        @endif
    </section>
@endsection
