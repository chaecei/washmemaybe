<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category'; 

    protected $fillable = [
        'order_id',
        'service_number',
        'status',
    ];
    public $timestamps = true;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
