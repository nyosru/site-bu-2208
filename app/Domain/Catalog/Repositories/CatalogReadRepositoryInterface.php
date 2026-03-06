<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Entities\CatalogNode;

interface CatalogReadRepositoryInterface
{
    /**
     * @return CatalogNode[]
     */
    public function getTree(?int $rootId = null): array;

    /**
     * @return CatalogNode[]
     */
    public function getChildren(?int $parentId = null): array;

    public function findById(int $id): ?CatalogNode;

    /**
     * @return int[]
     */
    public function getDescendantIdsWithSelf(int $id): array;
}
