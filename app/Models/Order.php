<?php

namespace App\Models;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'dish_id',
        'table_id',
        'user_id',
        'sub_total_price',
        'status',
    ];


    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }


    public function user()
    {
        return $this->hasMany(User::class);
    }
}
