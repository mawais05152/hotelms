<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetPriceHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_id',
        'old_price',
        'new_price',
        'added_quantity',
        'supplier_name',
        'warehouse_name',
        'note',
    ];

}
