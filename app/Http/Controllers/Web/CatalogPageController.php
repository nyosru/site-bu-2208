<?php

namespace App\Http\Controllers\Web;

use App\Application\Catalog\Services\CatalogQueryService;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Contracts\View\View;

class CatalogPageController extends Controller
{
    public function __construct(
        private readonly CatalogQueryService $catalogQueryService,
    ) {}

    public function show(int $id): View
    {
        $catalog = $this->catalogQueryService->getById($id);

        if ($catalog === null) {
            abort(404);
        }

        $children = $this->catalogQueryService->getChildren($id);
        $catalogIds = $this->catalogQueryService->getDescendantIdsWithSelf($id);
        $advertisements = Advertisement::query()
            ->with(['photos', 'user'])
            ->whereIn('catalog_id', $catalogIds)
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('catalog.show', [
            'catalog' => $catalog,
            'children' => $children,
            'advertisements' => $advertisements,
        ]);
    }
}
