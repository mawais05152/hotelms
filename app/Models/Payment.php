<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
     protected $fillable = ['order_id', 'sab_price', 'payment_method'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
