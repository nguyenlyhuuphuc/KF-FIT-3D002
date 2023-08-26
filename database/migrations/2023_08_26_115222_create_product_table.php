<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->float('price')->default(0);
            $table->float('discount_price')->default(0);
            $table->text('short_description')->nullable();
            $table->integer('qty')->default(0);
            $table->string('shipping',255)->nullable();
            $table->float('weight')->default(0);
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            

            //Buoc 1 tao column
            // $table->bigInteger('product_category_id')->unsigned();
            $table->unsignedBigInteger('product_category_id');
            //Buoc 2 tao khoa ngoai cho column do
            $table->foreign('product_category_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
