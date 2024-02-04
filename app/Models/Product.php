<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->hasMany(ImageProduct::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
