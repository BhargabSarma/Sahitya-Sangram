<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shiprocket_awb')->nullable()->after('status');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_awb');
            $table->text('shiprocket_response')->nullable()->after('shiprocket_shipment_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shiprocket_awb', 'shiprocket_shipment_id', 'shiprocket_response']);
        });
    }
};
