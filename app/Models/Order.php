<?php

namespace App\Models;

use App\Models\MessMenu;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['booking_table_id', 'person', 'date', 'time', 'status'];

    public function bookingTable()
    {
        return $this->belongsTo(BookingTable::class, 'booking_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'order_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
    public function OrderStatus()
    {
        return $this->hasMany(OrderStatus::class);
    }
    public function messmenu()
    {
        return $this->belongsTo(MessMenu::class);
    }
}
