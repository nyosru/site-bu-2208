<?php

namespace App\View\Components;

use App\Models\Cat;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopCatalogMenu extends Component
{
    public function render(): View
    {
        $catalogs = Cat::query()
            ->select(['id', 'name'])
            ->whereNull('cat_up_id')
            ->orderBy('name')
            ->get();

        $currentCatalogId = request()->route('id');
        $currentCatalogId = $currentCatalogId !== null ? (int) $currentCatalogId : null;

        $activeTopCatalogId = $this->resolveTopCatalogId($currentCatalogId);

        return view('components.top-catalog-menu', [
            'catalogs' => $catalogs,
            'activeTopCatalogId' => $activeTopCatalogId,
        ]);
    }

    private function resolveTopCatalogId(?int $catalogId): ?int
    {
        if ($catalogId === null) {
            return null;
        }

        $current = Cat::query()->select(['id', 'cat_up_id'])->find($catalogId);
        if ($current === null) {
            return null;
        }

        while ($current->cat_up_id !== null) {
            $current = Cat::query()->select(['id', 'cat_up_id'])->find((int) $current->cat_up_id);
            if ($current === null) {
                return null;
            }
        }

        return (int) $current->id;
    }
}
