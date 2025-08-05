<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultCourierPartnerTable extends Migration
{
    public function up()
    {
        Schema::create('default_courier_partner', function (Blueprint $table) {
            $table->id();
            $table->string('courier_company_id');
            $table->string('courier_name');
            $table->decimal('shipping_price', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('default_courier_partner');
    }
}
