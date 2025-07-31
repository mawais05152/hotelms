<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'name',
    //     'item_type',
    //     'product_id',
    //     'asset_id',
    //     'price',
    //     'total_quantity',
    //     'damaged_quantity',
    //     'available_qty'
    // ];
        protected $fillable = [
        'item_type',
        'product_id',
        'asset_id',
        'name',
        'total_quantity',
        'available_qty',
        'damaged_quantity',
        'price',
        'unit',
    ];



    public function getAvailableQuantityAttribute()
    {
        return $this->total_quantity - $this->damaged_quantity;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function asset()
    {
        return $this->belongsTo(RestaurantAsset::class, 'asset_id');
    }
    // public function variation()
    // {
    //     return $this->belongsTo(Variation::class, 'variation_id');
    // }


}
