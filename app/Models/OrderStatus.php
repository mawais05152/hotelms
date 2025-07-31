<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'status', 'delivered_by', 'updated_by', 'updated_at'];
    public $timestamps = true;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}
