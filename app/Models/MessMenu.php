<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessMenu extends Model
{
    use HasFactory;
    // protected $fillable = ['meal_name', 'date', 'cooked_by', 'cooked_for_persons', 'quantity_made'];
protected $fillable = [
    'meal_name',
    'date',
    'cooked_by',
    'cooked_for_persons',
    'quantity_made',
    'ingredient_name',
    'quantity_used',
    'unit',
];

public function variations()
{
    return $this->hasMany(DishVariation::class, 'mess_menu_id');
}

public function orders()
{
    return $this->hasMany(order::class, 'mess_menu_id');
}

}
