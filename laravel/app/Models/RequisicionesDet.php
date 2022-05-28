<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RequisicionesDet extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requisiciones_encs_id', 'inventario_id', 'cantidad_solicitada', 'cantidad_autorizada'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
