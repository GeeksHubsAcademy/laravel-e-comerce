<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
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
    public function orders()
    {
       return $this->belongsToMany('\App\Order');
    }
}
