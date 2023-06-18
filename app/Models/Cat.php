<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Good;

/*
 * @OA\Definition(
 *  definition="Каталоги",
 * 
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="cat_up_id",
 *      type="integer"
 *  )
 * 
 * @OA\Property(
 *     title="Created at",
 *     description="Created at",
 *     example="2020-01-27 17:50:45",
 *     format="datetime",
 *     type="string"
 *  )
 * 
 * @OA\Property(
 *     title="Updated at",
 *     description="Updated at",
 *     example="2020-01-27 17:50:45",
 *     format="datetime",
 *     type="string"
 *  )
 * 
 * )
 */
class Cat extends Model
{
    use HasFactory;

    // /**
    //  * Получить комментарии к посту блога.
    //  */
    // public function goods()
    // {
    //     return $this->hasMany(Good::class, 'cat_id', 'id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(Self::class, 'cat_up_id', 'id')
    //         ->with('children');
    // }

    public function children()
    {
        return $this->hasMany(Cat::class, 'cat_up_id')->with('children');
    }



}