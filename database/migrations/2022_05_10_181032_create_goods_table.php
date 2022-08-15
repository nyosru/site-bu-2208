<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('opis')
                ->nullable();
            $table->float('price', 10, 2)
                ->nullable();
            $table->enum('type', ['sell', 'renta', 'buy', 'need_renta']);

            $table->integer('cat_id')
                ->nullable()
                // ->unique()
            ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
