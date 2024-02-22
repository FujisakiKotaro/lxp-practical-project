<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::create('reviews', function (Blueprint $table) {
    //         $table->increments('id');
    //         $table->foreignId('productID')->constrained('products');
    //         $table->foreignId('userID')->constrained('customers');
    //         // $table->increments('productID');
    //         // $table->increments('userID');
    //         $table->integer('rank');
    //         $table->text('comment');
    //         $table->timestamps();
    //     });
    // }

    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            
            // productID 列のデータ型を integer に指定
            $table->unsignedInteger('productID')->index();
            
            // userID 列のデータ型も integer に指定
            $table->unsignedInteger('userID')->index();
            
            $table->integer('rank');
            $table->text('comment');
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
        Schema::dropIfExists('reviews');
    }
}
