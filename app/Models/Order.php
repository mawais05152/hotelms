<?php

namespace App\Models;

use App\Models\MessMenu;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    // protected $fillable = ['booking_table_id', 'person', 'date', 'time', 'status','payment_status'];

    protected $fillable = ['booking_id','person','date','time','order_by','delivered_by','order_type','status','category_id','product_id','variation_id','quantity','price','sub_total','payment_status'];
    public function bookingTable()
    {
        return $this->belongsTo(BookingTable::class, 'booking_id');
    }

    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'order_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function OrderStatus()
    {
        return $this->hasMany(OrderStatus::class);
    }
    public function messmenu()
    {
        return $this->belongsTo(MessMenu::class);
    }

    //event relation with order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'order_by');
    }


}
