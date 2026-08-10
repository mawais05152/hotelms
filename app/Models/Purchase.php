<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
   protected $fillable = [
    'invoice_no',
    'name',
    'asset_type',
    'variation_id',
    'total_quantity',
    'price',
    'supplier_name',
    'purchase_date',
    'notes',
];
public function StockItem()
{
    return $this->hasOne(StockItem::class, 'item_id')->where('item_type', 'asset');
}
public function variation()
{
    return $this->belongsTo(Variation::class, 'variation_id');
}
// public function product()
// {
//     return $this->belongsTo(Product::class);
// }

// public function asset()
// {
//     return $this->belongsTo(RestaurantAsset::class);
// }
}
