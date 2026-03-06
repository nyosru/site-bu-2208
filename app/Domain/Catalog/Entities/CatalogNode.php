<?php

namespace App\Domain\Catalog\Entities;

class CatalogNode
{
    /**
     * @param CatalogNode[] $children
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?int $parentId,
        public readonly array $children = [],
    ) {
    }
}
