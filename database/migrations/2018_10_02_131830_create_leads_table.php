<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_serial');
            $table->integer('supplier_serial');
            $table->string('product_id');
            $table->integer('supplier_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->text('note');
            $table->string('order_id')->nullable();
            $table->string('publisher_id')->nullable();
            $table->integer('status_admin')->default(3);
            $table->integer('status_caller')->default(3);
            $table->integer('caller_id')->default(0);
            $table->timestamp('update_admin')->nullable();
            $table->timestamp('update_caller')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('leads');
    }
}
