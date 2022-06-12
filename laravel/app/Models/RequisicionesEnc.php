<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RequisicionesEnc extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario_creo', 'fecha_creo', 'usuario_autorizo', 'fecha_autorizo', 'usuario_aprobo',
        'fecha_aprobo', 'estado_requisicion','usuario_despacho','fecha_despacho', 'observaciones','correlativo'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
