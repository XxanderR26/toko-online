<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

        protected $fillable = [
        'category_id',
        'name',
        'description',
        'price_per_kg',
        'stock',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
