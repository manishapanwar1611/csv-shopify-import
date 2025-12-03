<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_import_log extends Model
{
    protected $fillable = [
        'upload_id', 'level', 'title', 'message'
    ];
}
