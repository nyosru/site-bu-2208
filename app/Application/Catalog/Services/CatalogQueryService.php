<?php

namespace App\Application\Catalog\Services;

use App\Application\Catalog\DTO\CatalogNodeDto;
use App\Domain\Catalog\Entities\CatalogNode;
use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;

class CatalogQueryService
{
    public function __construct(
        private readonly CatalogReadRepositoryInterface $catalogRepository,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTree(?int $rootId = null): array
    {
        return array_map(
            fn (CatalogNode $node) => $this->mapToDto($node)->toArray(),
            $this->catalogRepository->getTree($rootId)
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getChildren(?int $parentId = null): array
    {
        return array_map(
            fn (CatalogNode $node) => $this->mapToDto($node)->toArray(),
            $this->catalogRepository->getChildren($parentId)
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getById(int $id): ?array
    {
        $node = $this->catalogRepository->findById($id);

        if ($node === null) {
            return null;
        }

        return $this->mapToDto($node)->toArray();
    }

    /**
     * @return int[]
     */
    public function getDescendantIdsWithSelf(int $id): array
    {
        return $this->catalogRepository->getDescendantIdsWithSelf($id);
    }

    /**
     * @return int[]
     */
    public function getCurrentAndDescendantIds(int $id): array
    {
        return $this->getDescendantIdsWithSelf($id);
    }

    private function mapToDto(CatalogNode $node): CatalogNodeDto
    {
        return new CatalogNodeDto(
            id: $node->id,
            name: $node->name,
            parentId: $node->parentId,
            children: array_map(fn (CatalogNode $child) => $this->mapToDto($child), $node->children),
        );
    }

}
