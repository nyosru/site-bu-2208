<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CatalogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return $this->collection;
        // return
        //     // [
        //         // 'data' => $this->collection,
        //            $this->collection
        //         // 'links' => [
        //         //     'self' => 'link-value',
        //         // ],
        //     ];
    }
}
