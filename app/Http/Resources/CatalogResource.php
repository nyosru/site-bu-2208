<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [ 
            'id' => $this->id ,
            // 'a_id' => $this->a_id ,
            'name' => $this->name ,
            'cat_up_id' => $this->cat_up_id ,
            // 'a_price' => $this->a_price ,
            // 'a_categoryid' => $this->a_categoryid ,
            // 'a_arrayimage' => $this->a_arrayimage ,
        ];
    }
}
