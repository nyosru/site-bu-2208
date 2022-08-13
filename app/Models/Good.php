<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\GoodImage;
use App\Models\Cat;

class Good extends Model
{
    use HasFactory;

     /**
     * Получить комментарии к посту блога.
     */
    public function images()
    {
        return $this->hasMany(GoodImage::class, 'good_id' , 'id' );
    }

    public function cat()
    {
        // return $this->belongsTo(Cat::class,'cat_id','id');
        return $this->belongsTo(Cat::class);
    }
}
