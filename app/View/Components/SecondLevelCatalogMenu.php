<?php

namespace App\View\Components;

use App\Models\Cat;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SecondLevelCatalogMenu extends Component
{
    public function render(): View
    {
        $currentCatalogId = request()->route('id');
        $currentCatalogId = $currentCatalogId !== null ? (int) $currentCatalogId : null;

        $activeTopCatalogId = $this->resolveTopCatalogId($currentCatalogId);

        $catalogs = collect();
        if ($activeTopCatalogId !== null) {
            $catalogs = Cat::query()
                ->select(['id', 'name'])
                ->where('cat_up_id', $activeTopCatalogId)
                ->orderBy('name')
                ->get();
        }

        return view('components.second-level-catalog-menu', [
            'catalogs' => $catalogs,
            'activeCatalogId' => $currentCatalogId,
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
