<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    public function weight()
    {
        return $this->hasMany(Weight::class);
    }

    public function size()
    {
        return $this->hasMany(Size::class);
    }
}
