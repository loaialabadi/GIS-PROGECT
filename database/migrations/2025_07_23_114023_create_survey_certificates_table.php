<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_survey_certificates_table.php

    public function up()
    {
        Schema::create('survey_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            
                        $table->string('transaction_number')->unique();

            $table->string('applicant_name');           // اسم صاحب الطلب
            $table->string('location_address')->nullable();   // عنوان القطعة
            $table->string('location_description')->nullable();  // وصف القطعة
            $table->string('area_size')->nullable();    // مساحة القطعة
            $table->string('requesting_entity')->nullable(); // الجهة الطالبة
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_certificates');
    }
};
