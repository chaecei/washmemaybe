<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's pluralization rule
    protected $table = 'category'; 

    protected $fillable = [
        'service_number',
        'status',
        'grand_total',
        'days_unclaimed',
        'name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
