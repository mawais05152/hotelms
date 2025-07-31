<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['mess_meal_id', 'ingredient_name', 'quantity_used', 'unit'];

    // public function messMenu()
    // {
    //     return $this->belongsTo(MessMenu::class, 'mess_meal_id');
    // }
}
