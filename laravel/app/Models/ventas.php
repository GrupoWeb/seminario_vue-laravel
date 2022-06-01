<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ventas extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'id', 'factura_id', 'fecha_creado','monto_total'
   ];

   protected $dates = [
    'deleted_at'
    ];
}
