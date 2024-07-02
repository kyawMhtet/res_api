<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
