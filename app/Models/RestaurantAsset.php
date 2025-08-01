<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantAsset extends Model
{
    use HasFactory;

protected $fillable = ['name', 'category'];

 public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
