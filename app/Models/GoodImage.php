<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Good;

use Illuminate\Support\Facades\Storage;

class GoodImage extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые должны быть преобразованы в дату
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getImgAttribute($value)
    {
        // if (strpos($this->attributes['name'], 'http')) {
        //     return $this->attributes['name'];
        // } else {
            return Storage::url($this->attributes['name']);
        // }
    }

    // /**
    //  * Получить пост, которому принадлежит комментарий.
    //  */
    // public function good()
    // {
    //     return $this->belongsTo(Good::class, 'good_id');
    // }
}
