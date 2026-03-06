<?php

namespace App\Application\Catalog\DTO;

class CatalogNodeDto
{
    /**
     * @param CatalogNodeDto[] $children
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?int $parentId,
        public readonly array $children = [],
    ) {
    }

    /**
     * @return array{id:int,name:string,cat_up_id:int|null,children:array<int,mixed>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cat_up_id' => $this->parentId,
            'children' => array_map(static fn (self $child) => $child->toArray(), $this->children),
        ];
    }
}
