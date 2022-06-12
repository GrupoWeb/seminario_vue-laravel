<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SedeEmpresa extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'id', 'sede_id','empresa_id'
   ];

   protected $dates = [
       'deleted_at'
   ];
}
