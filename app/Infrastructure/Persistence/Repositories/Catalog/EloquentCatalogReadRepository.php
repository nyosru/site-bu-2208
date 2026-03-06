<?php

namespace App\Infrastructure\Persistence\Repositories\Catalog;

use App\Domain\Catalog\Entities\CatalogNode;
use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;
use App\Models\Cat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentCatalogReadRepository implements CatalogReadRepositoryInterface
{
    public function getTree(?int $rootId = null): array
    {
        if ($rootId === null) {
            $rows = Cat::query()
                ->select(['id', 'name', 'cat_up_id'])
                ->orderBy('id')
                ->get();

            return $this->buildTreeFromRows($rows, null);
        }

        $rows = collect(DB::select(
            <<<'SQL'
WITH RECURSIVE catalog_tree AS (
    SELECT id, name, cat_up_id
    FROM cats
    WHERE id = ?

    UNION ALL

    SELECT c.id, c.name, c.cat_up_id
    FROM cats c
    INNER JOIN catalog_tree ct ON c.cat_up_id = ct.id
)
SELECT id, name, cat_up_id
FROM catalog_tree
ORDER BY id
SQL,
            [$rootId]
        ));

        return $this->buildTreeFromRows($rows, $rootId);
    }

    public function getChildren(?int $parentId = null): array
    {
        $query = Cat::query()->select(['id', 'name', 'cat_up_id'])->orderBy('name');

        if ($parentId === null) {
            $query->whereNull('cat_up_id');
        } else {
            $query->where('cat_up_id', $parentId);
        }

        return $query->get()
            ->map(fn (Cat $cat) => new CatalogNode(
                id: (int) $cat->id,
                name: (string) $cat->name,
                parentId: $cat->cat_up_id === null ? null : (int) $cat->cat_up_id,
                children: [],
            ))
            ->all();
    }

    public function findById(int $id): ?CatalogNode
    {
        $cat = Cat::query()->find($id);

        if ($cat === null) {
            return null;
        }

        return new CatalogNode(
            id: (int) $cat->id,
            name: (string) $cat->name,
            parentId: $cat->cat_up_id === null ? null : (int) $cat->cat_up_id,
            children: [],
        );
    }

    public function getDescendantIdsWithSelf(int $id): array
    {
        return collect(DB::select(
            <<<'SQL'
WITH RECURSIVE catalog_tree AS (
    SELECT id, cat_up_id
    FROM cats
    WHERE id = ?

    UNION ALL

    SELECT c.id, c.cat_up_id
    FROM cats c
    INNER JOIN catalog_tree ct ON c.cat_up_id = ct.id
)
SELECT id
FROM catalog_tree
ORDER BY id
SQL,
            [$id]
        ))
            ->pluck('id')
            ->map(fn ($nodeId) => (int) $nodeId)
            ->all();
    }

    private function buildTreeFromRows(Collection $rows, ?int $rootId): array
    {
        $byParent = $rows->groupBy(fn ($row) => $row->cat_up_id === null ? 'root' : (string) $row->cat_up_id);

        $buildNode = function (object $row) use (&$buildNode, $byParent): CatalogNode {
            $childrenRows = $byParent->get((string) $row->id, collect());

            return new CatalogNode(
                id: (int) $row->id,
                name: (string) $row->name,
                parentId: $row->cat_up_id === null ? null : (int) $row->cat_up_id,
                children: $childrenRows->map(fn (object $childRow) => $buildNode($childRow))->all(),
            );
        };

        $rootRows = $rootId === null
            ? $byParent->get('root', collect())
            : $rows->filter(fn ($row) => (int) $row->id === $rootId)->values();

        return $rootRows->map(fn (object $row) => $buildNode($row))->all();
    }
}
