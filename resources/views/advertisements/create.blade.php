@extends('layouts.app')

@section('content')
    <section class="auth-card-wrap">
        <div class="auth-card ad-create-card">
            <h2>Добавить объявление</h2>
            <p class="muted-text">Шаг {{ $step }} из 2</p>

            @if($step === 1)
                <form action="{{ route('advertisements.step-two') }}" method="POST" class="auth-form">
                    @csrf

                    <div>
                        <span>Категория объявления</span>
                        <livewire:advertisement.category-selector :selected-catalog-id="(int) old('catalog_id', $catalogId)" />
                        @error('catalog_id')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    <fieldset class="type-fieldset">
                        <legend>Тип объявления</legend>
                        <label class="inline-option">
                            <input type="radio" name="ad_type" value="sell" @checked(old('ad_type', $adType) === 'sell')>
                            <span>Продаю</span>
                        </label>
                        <label class="inline-option">
                            <input type="radio" name="ad_type" value="buy" @checked(old('ad_type', $adType) === 'buy')>
                            <span>Ищу, хочу купить</span>
                        </label>
                        @error('ad_type')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </fieldset>

                    <button type="submit" id="step-one-submit">Продолжить</button>
                </form>
            @else
                <p class="muted-text">Категория: {{ $catalogName }}. Тип: {{ $adType === 'buy' ? 'Ищу, хочу купить' : 'Продаю' }}</p>

                <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" class="auth-form">
                    @csrf
                    <input type="hidden" name="catalog_id" value="{{ old('catalog_id', $catalogId) }}">
                    <input type="hidden" name="ad_type" value="{{ old('ad_type', $adType) }}">

                    <label>
                        <span>Название</span>
                        <input type="text" name="title" value="{{ old('title') }}" maxlength="255" required>
                        @error('title')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </label>

                    <label>
                        <span>Фотографии</span>
                        <input type="file" name="photos[]" accept="image/*" multiple required>
                        @error('photos')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                        @error('photos.*')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </label>

                    <label>
                        <span>Описание</span>
                        <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </label>

                    <label>
                        <span>Цена</span>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                        @error('price')
                        <small class="error-text">{{ $message }}</small>
                        @enderror
                    </label>

                    @error('catalog_id')
                    <small class="error-text">{{ $message }}</small>
                    @enderror

                    @error('ad_type')
                    <small class="error-text">{{ $message }}</small>
                    @enderror

                    <div class="step-actions">
                        <a href="{{ route('advertisements.create', ['step' => 1, 'catalog_id' => old('catalog_id', $catalogId), 'ad_type' => old('ad_type', $adType)]) }}" class="rounded-lg border border-blue-700 bg-transparent px-3.5 py-2 font-semibold text-blue-700 no-underline">
                            Назад к шагу 1
                        </a>
                        <button type="submit">Отправить объявление</button>
                    </div>
                </form>
            @endif
        </div>
    </section>
@endsection
