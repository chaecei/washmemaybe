<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Customer;

class Order extends Model
{
    use HasFactory;

    // Table associated with the model (if not using the default 'orders' table name)
    protected $table = 'orders';

    // Fields that are mass assignable
    protected $fillable = [
        'customer_id', // Foreign key that links to the customer
        'id',
        'grand_total',
    ];

    /**
     * Define the inverse relationship: Each order belongs to one customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class); // Each order belongs to a customer
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'order_id');
    }


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getGrandTotalAttribute()
    {
        return optional($this->items)->sum(function ($item) {
            return $item->total_load_price + $item->detergent_price + $item->softener_price;
        }) ?? 0;
    }



}
