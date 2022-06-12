<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class facturas extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'id', 'cliente_id', 'despacho_id','tipo_pagos_id','vendedor_id','fecha_creado','monto_total','fel'
   ];

   protected $dates = [
    'deleted_at'
];
      
}
