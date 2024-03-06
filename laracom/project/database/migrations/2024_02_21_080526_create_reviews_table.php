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

    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            
            // productID 列のデータ型を integer に指定
            $table->unsignedInteger('product_id')->index();
            
            // userID 列のデータ型も integer に指定
            $table->unsignedInteger('customer_id')->index();
            
            $table->integer('rank');
            $table->text('comment');
            $table->timestamps();

            // 外部制約
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('customer_id')->references('id')->on('customers');
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
