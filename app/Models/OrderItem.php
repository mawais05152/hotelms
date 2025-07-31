<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'category_id', 'quantity', 'price', 'sub_total'];

public function product() {
    return $this->belongsTo(Product::class);
}

public function category() {
    return $this->belongsTo(Category::class);
}

public function order() {
    return $this->belongsTo(Order::class);
}
public function variation()
{
    return $this->belongsTo(Variation::class, 'variation_id');
}

}
