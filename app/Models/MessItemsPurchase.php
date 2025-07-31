<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessItemsPurchase extends Model
{
    use HasFactory;
    protected $fillable = ['ingredient_name', 'quantity', 'unit', 'price_per_unit', 'total_cost', 'purchased_by', 'purchased_at'];

}
