<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table='commentable'; //si me salgo de la convención con el nombre de la tabla he de especificarlo aquí
    protected $guarded = [];
    public function commentable() //puede pertenecer a un User o a un Product
    {
        return $this->morphTo(); //equivalente a belongsTo pero a multiples modelos
    }
    public function user()
    {
       return $this->belongsTo('\App\User');//los comentarios pertenecen al usuario que los hace campo user_id
    }
}
