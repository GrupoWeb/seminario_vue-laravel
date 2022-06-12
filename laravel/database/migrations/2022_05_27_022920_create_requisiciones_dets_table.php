<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisicionesDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisiciones_dets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisiciones_encs_id')->constrained()->nullable();;
            $table->foreignId('inventario_id')->constrained()->nullable();;
            $table->integer('cantidad_solicitada')->nullable();;
            $table->integer('cantidad_autorizada')->nullable();;
            $table->timestamps();

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
        Schema::dropIfExists('requisiciones_dets');
    }
}
