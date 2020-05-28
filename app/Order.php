<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];//guarded es para especificar los atributos que no se pueden inyectar a través del modelo, si esta vacío significa que se pueden meter todos los atributos
    // protected $fillable = [//fillable es para especificar los atributos que se pueden añadir, si esta vacío no se puede añadir ninguno
    //     'status',
    //     'deliveryDate',
    //     'user_id'
    // ];
    public function products()
    {
        return $this->belongsToMany('\App\Product');
    }
    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
