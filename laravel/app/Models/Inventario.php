<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Inventario extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'id', 'producto_id', 'medida_id','proveedores_id','path_imagen','precio','stock','sede_empresa_id'
   ];

   protected $dates = [
       'deleted_at'
   ];
}
