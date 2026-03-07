<?php

namespace App\Livewire\Advertisement;

use App\Models\Cat;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CategorySelector extends Component
{
    /**
     * @var array<int, int>
     */
    public array $selectedByLevel = [];

    /**
     * @var array<int, array<int, array{id:int,name:string}>>
     */
    public array $levels = [];

    public ?int $finalCatalogId = null;

    public function mount(?int $selectedCatalogId = null): void
    {
        $this->levels = [];
        $this->selectedByLevel = [];

        $path = $this->buildPath($selectedCatalogId);
        if ($path === []) {
            $this->appendLevel(null);
            $this->syncFinalCatalogId();

            return;
        }

        $parentId = null;
        foreach ($path as $level => $catalogId) {
            $this->appendLevel($parentId);
            $this->selectedByLevel[$level] = $catalogId;
            $parentId = $catalogId;
        }

        if ($this->hasChildren($parentId)) {
            $this->appendLevel($parentId);
        }

        $this->syncFinalCatalogId();
    }

    public function selectLevel(int $level, mixed $value): void
    {
        $selectedId = (int) $value;

        $this->selectedByLevel = array_slice($this->selectedByLevel, 0, $level + 1);
        $this->selectedByLevel[$level] = $selectedId;
        $this->levels = array_slice($this->levels, 0, $level + 1);

        if ($selectedId > 0 && $this->hasChildren($selectedId)) {
            $this->appendLevel($selectedId);
        }

        $this->syncFinalCatalogId();
    }

    public function render(): View
    {
        return view('livewire.advertisement.category-selector');
    }

    private function appendLevel(?int $parentId): void
    {
        $this->levels[] = $this->childrenQuery($parentId)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(static fn (Cat $cat): array => [
                'id' => (int) $cat->id,
                'name' => $cat->name,
            ])
            ->values()
            ->all();
    }

    /**
     * @return int[]
     */
    private function buildPath(?int $selectedCatalogId): array
    {
        $selectedId = (int) $selectedCatalogId;
        if ($selectedId <= 0) {
            return [];
        }

        $path = [];
        $currentId = $selectedId;

        while ($currentId > 0) {
            $current = Cat::query()->find($currentId);
            if ($current === null) {
                return [];
            }

            array_unshift($path, (int) $current->id);

            $parentId = $current->cat_up_id !== null ? (int) $current->cat_up_id : null;
            if ($parentId === null || $parentId <= 0) {
                break;
            }

            $parentExists = Cat::query()->whereKey($parentId)->exists();
            if (! $parentExists) {
                break;
            }

            $currentId = $parentId;
        }

        return $path;
    }

    private function hasChildren(?int $catalogId): bool
    {
        if ($catalogId === null || $catalogId <= 0) {
            return false;
        }

        return Cat::query()->where('cat_up_id', $catalogId)->exists();
    }

    private function syncFinalCatalogId(): void
    {
        $lastValue = end($this->selectedByLevel);
        $selectedId = (int) ($lastValue ?: 0);

        if ($selectedId <= 0 || $this->hasChildren($selectedId)) {
            $this->finalCatalogId = null;

            return;
        }

        $this->finalCatalogId = $selectedId;
    }

    private function childrenQuery(?int $parentId): Builder
    {
        if ($parentId === null || $parentId <= 0) {
            return Cat::query()->where(static function (Builder $query): void {
                $query->whereNull('cat_up_id')->orWhere('cat_up_id', 0);
            });
        }

        return Cat::query()->where('cat_up_id', $parentId);
    }
}
