<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Good;

class Cat extends Model
{
    use HasFactory;

    /**
     * Получить комментарии к посту блога.
     */
    public function goods()
    {
        return $this->hasMany(Good::class, 'cat_id', 'id');
    }

    public function children() {
        return $this->hasMany(Self::class, 'cat_up_id' , 'id')
                    ->with('children');
    }
}
