<div class="category-selects">
    @foreach($levels as $index => $options)
        <label class="category-level" wire:key="category-level-{{ $index }}">
            <span>Уровень категории {{ $index + 1 }}</span>
            <select wire:change="selectLevel({{ $index }}, $event.target.value)" required>
                <option value="">Выберите категорию</option>
                @foreach($options as $option)
                    <option value="{{ $option['id'] }}" @selected((int) ($selectedByLevel[$index] ?? 0) === (int) $option['id'])>
                        {{ $option['name'] }}
                    </option>
                @endforeach
            </select>
        </label>
    @endforeach

    <input type="hidden" name="catalog_id" value="{{ $finalCatalogId ?? '' }}">

    @if($finalCatalogId === null)
        <small class="muted-text">Выберите конечную подкатегорию.</small>
    @endif
</div>
