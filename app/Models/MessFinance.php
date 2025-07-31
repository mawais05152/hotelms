<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessFinance extends Model
{
    use HasFactory;
    protected $fillable = ['mess_meal_id', 'total_cost', 'price_per_person', 'persons_served', 'total_income', 'profit_or_loss'];

     public function messMenu()
    {
        return $this->belongsTo(MessMenu::class, 'mess_meal_id');
    }
}
