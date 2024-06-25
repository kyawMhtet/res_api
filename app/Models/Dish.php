<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'image',
        'name',
        'price',
        'description'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return url('/images/' . $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}