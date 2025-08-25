<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tracking_certificates', function (Blueprint $table) {
            $table->id();

            // معلومات المعاملة
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->string('client_name');          
            $table->string('national_id');          
            $table->string('purpose'); // الغرض من الشهادة
            $table->string('coordinates')->nullable(); // الاحداثي
            $table->text('building_description')->nullable();
            $table->string('center_name')->nullable();
            $table->string('area')->nullable();

            // الحالة والتتبع
            $table->json('tracking_status')->nullable();  // تخزين الحالة كـ JSON
            $table->tinyInteger('delivery_status')->default(1)->comment('0=لم يتم، 1=مفتش، 2=GIS، 3=نهائي');

            // بيانات التسليم
            $table->boolean('is_delivered')->default(false);
            $table->string('deliverer_name')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // ملفات وملاحظات
            $table->string('certificate_path')->nullable();
            $table->text('notes')->nullable();

            // الموظفين المشاركين
            $table->string('inspector_name')->nullable();
            $table->string('gis_preparer_name')->nullable();
            $table->string('gis_reviewer_name')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking_certificates');
    }
};
