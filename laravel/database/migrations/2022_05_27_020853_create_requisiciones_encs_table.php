<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisicionesEncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisiciones_encs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('usuario_creo')->unsigned();
            $table->date('fecha_creo');
            $table->bigInteger('usuario_autorizo')->unsigned();
            $table->date('fecha_autorizo');
            $table->bigInteger('usuario_aprobo')->unsigned();
            $table->date('fecha_aprobo');
            $table->bigInteger('estado_requisicion')->unsigned();
            $table->bigInteger('usuario_despacho')->unsigned();
            $table->date('fecha_despacho');
            $table->text('observaciones');
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('usuario_creo')->references('id')->on('users');
            $table->foreign('usuario_autorizo')->references('id')->on('users');
            $table->foreign('usuario_aprobo')->references('id')->on('users');
            $table->foreign('estado_requisicion')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisiciones_encs');
    }
}
