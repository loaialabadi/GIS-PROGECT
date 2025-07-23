<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_tracking_certificates_table.php

    public function up()
    {
        Schema::create('tracking_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('client_name');          // اسم العميل
            $table->string('national_id');          // رقم البطاقة
            $table->string('transaction_number');  // رقم المعاملة
            $table->text('building_description');  // وصف المبنى
            $table->string('project_name')->nullable(); // اسم المشروع (اختياري)
            $table->string('area')->nullable();         // المنطقة
            $table->timestamp('tracking_date')->nullable(); // تاريخ المتابعة
            $table->text('notes')->nullable();           // الملاحظات
            $table->string('inspector_name')->nullable(); // اسم القائم بالمتابعة
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_certificates');
    }
};
