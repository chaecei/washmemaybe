<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Table associated with the model (if not using the default 'orders' table name)
    protected $table = 'orders';

    // Fields that are mass assignable
    protected $fillable = [
        'customer_id', // Foreign key that links to the customer
        'id',
        'service_type',
        'total_load',
        'detergent',
        'softener',
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
        return $this->belongsTo(Category::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
