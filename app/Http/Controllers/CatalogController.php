<?php

namespace App\Http\Controllers;

use App\Http\Resources\CatalogCollection;

use App\Models\Cat;

class CatalogController extends Controller
{
    /*
     * список всех каталогов
     */
    public function index()
    {
        return new CatalogCollection(
            Cat:: 
                // remember(60)->
                // with('icon')->
                // with('children')->
                // with('cats')->
                // where('status', 'show')->orderBy('sort')->
                // whereNull('cat_up_id')->
                // orderBy('sort')->
                get()
        );
    }

    /**
     * показ одного каталога
     */
    public function show(string $id)
    {
        return new CatalogCollection(Cat::where('id', $id)->
            // where('status', 'show')->
            get());
    }
}
