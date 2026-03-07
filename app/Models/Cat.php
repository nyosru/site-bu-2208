<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $fillable = [
        'name',
        'cat_up_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'cat_up_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'cat_up_id');
    }
}
