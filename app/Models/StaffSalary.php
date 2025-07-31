<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'salary_amount', 'salary_month', 'paid_date', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

