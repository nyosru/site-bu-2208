<?php

namespace App\Http\Controllers;

use App\Application\Catalog\Services\CatalogQueryService;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CatalogQueryService $catalogQueryService,
    ) {
    }

    public function index()
    {
        return response()->json($this->catalogQueryService->getTree());
    }

    public function show0(string $id)
    {
        $catalog = $this->catalogQueryService->getById((int) $id);

        if ($catalog === null) {
            abort(404);
        }

        return response()->json([$catalog]);
    }

    public function showIn(?string $id = null)
    {
        $parentId = ($id === null || $id === '') ? null : (int) $id;

        return response()->json($this->catalogQueryService->getChildren($parentId));
    }

    public function showTree(string $id)
    {
        return response()->json($this->catalogQueryService->getTree((int) $id));
    }
}
