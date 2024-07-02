<?php

namespace App\Models;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'user_id',
        'quantity',
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'menu_id');
    }
}
