<?php

namespace App\Livewire\Catalog;

use App\Models\Cat;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Breadcrumbs extends Component
{
    /**
     * @var array<int, array{id:int,name:string}>
     */
    public array $items = [];

    public function mount(): void
    {
        $catalogId = request()->route('id');
        $catalogId = $catalogId !== null ? (int) $catalogId : null;

        if ($catalogId === null || $catalogId <= 0) {
            return;
        }

        $this->items = $this->buildPath($catalogId);
    }

    public function render(): View
    {
        return view('livewire.catalog.breadcrumbs');
    }

    /**
     * @return array<int, array{id:int,name:string}>
     */
    private function buildPath(int $catalogId): array
    {
        $path = [];
        $visited = [];
        $currentId = $catalogId;

        while ($currentId > 0 && ! isset($visited[$currentId])) {
            $visited[$currentId] = true;

            $current = Cat::query()
                ->select(['id', 'name', 'cat_up_id'])
                ->find($currentId);

            if ($current === null) {
                break;
            }

            array_unshift($path, [
                'id' => (int) $current->id,
                'name' => $current->name,
            ]);

            $parentId = $current->cat_up_id !== null ? (int) $current->cat_up_id : 0;
            if ($parentId <= 0) {
                break;
            }

            $currentId = $parentId;
        }

        return $path;
    }
}
