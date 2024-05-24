<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function images()
    {
        return $this->hasMany(ImageProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toSearchableArray()
    {
        return [
            'price' => $this->price,
            'created_at' => $this->created_at,
            'name' => $this->name,
        ];
    }
}
