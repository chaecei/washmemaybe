<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Specify the table name if it doesn't follow Laravel's pluralization rule
    protected $table = 'category'; 

    protected $fillable = [
        'order_id',
        'service_number',
        'status',
        'days_unclaimed',
        'picked_up_at',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id'); // Each category belongs to an order
    }

}
