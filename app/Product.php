<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image_path'
    ];
    public function categories()
    {
        return $this->belongsToMany('\App\Category');
    }
}
