<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

        // Table associated with the model (if not using the default 'orders' table name)
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'service_type',
        'total_load',
        'detergent',
        'softener',
        'total_load_price',
        'detergent_price',
        'softener_price',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
