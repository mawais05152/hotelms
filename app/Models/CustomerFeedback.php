<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'feedback_text', 'rating'];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
