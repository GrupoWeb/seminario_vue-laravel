<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained();
            $table->bigInteger('despacho_id')->unsigned();
            $table->foreignId('tipo_pagos_id')->constrained();
            $table->bigInteger('vendedor_id')->unsigned();
            $table->date('fecha_creado');
            $table->decimal('monto_total',6);
            $table->timestamps();


            $table->foreign('despacho_id')->references('id')->on('requisiciones_encs');
            $table->foreign('vendedor_id')->references('id')->on('users');


            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
