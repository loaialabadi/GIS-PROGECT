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
// داخل الميجريشن:
Schema::table('tracking_certificates', function (Blueprint $table) {
    $table->tinyInteger('delivery_status')->default(1)->comment('0=لم يتم، 1=مفتش، 2=GIS، 3=نهائي');
    $table->timestamp('delivered_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracking_certificates', function (Blueprint $table) {
            //
        });
    }
};
