<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Table associated with the model (if not using the default 'customers' table name)
    protected $table = 'customers';

    // Fields that are mass assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
    ];

    /**
     * Define the relationship: One customer can have many orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id'); // One customer can place many orders
    }

}
