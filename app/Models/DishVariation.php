<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'mess_menu_id',
        'name',
        'price',
    ];

    public function dish()
    {
        return $this->belongsTo(MessMenu::class, 'mess_menu_id');
    }
}


