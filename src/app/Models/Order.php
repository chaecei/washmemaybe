<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Customer;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'id',
        'grand_total',
    ];

      protected $casts = [
        'grand_total' => 'float',
    ];
    
    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'order_id');
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCalculatedGrandTotalAttribute()
    {
        return optional($this->items)->sum(function ($item) {
            return $item->total_load_price + $item->detergent_price + $item->softener_price;
        }) ?? 0;
    }



}
