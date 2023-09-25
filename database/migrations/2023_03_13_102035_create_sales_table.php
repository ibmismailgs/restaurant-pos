<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('order_no');
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->double('grand_total')->default(0);
            $table->double('discount')->default(0)->nullable();
            $table->double('tax_amount')->default(0)->nullable();
            $table->integer('total_quantity')->default(0);
            $table->tinyInteger('payment_type')->default(1)->comment('1 = Cash | 2 = Card');
            $table->string('transaction_id')->nullable();
            $table->double('change_amount')->default(0)->nullable();
            $table->text('note')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
