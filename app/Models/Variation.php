<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $fillable = ['unit', 'size', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }

    public function damagedItems()
    {
        return $this->hasMany(DamagedItem::class, 'variation_id');
    }
}
