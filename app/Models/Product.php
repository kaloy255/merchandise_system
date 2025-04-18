<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'quantity', 'price', 'image', 'description','category'];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
