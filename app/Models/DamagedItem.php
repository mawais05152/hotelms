<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamagedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        // 'item_id',
        'stock_item_id',
        'user_id',
        'variation_id',
        'quantity',
        'damage_date',
        'fine_amount',
        'reason',
    ];

    public function stockItem()
{
    return $this->belongsTo(StockItem::class, 'stock_item_id');
}

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedFineAttribute()
    {
        return 'Rs. ' . number_format($this->fine_amount, 2);
    }
}
