<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_upload extends Model
{
    public function Product()
    {
        $this->hasMany(Product::class);
    }
}
