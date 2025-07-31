<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessDistribution extends Model
{
    use HasFactory;
    protected $fillable = ['mess_meal_id', 'person_name', 'quantity_given', 'remarks', 'delivered_at'];

    public function messMenu()
    {
        return $this->belongsTo(MessMenu::class, 'mess_meal_id');
    }
}
