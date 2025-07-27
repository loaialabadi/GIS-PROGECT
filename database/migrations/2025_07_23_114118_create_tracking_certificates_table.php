<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tracking_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('client_name');          
            $table->string('national_id');          
            $table->string('transaction_number')->unique();
            $table->text('building_description')->nullable();
            $table->string('center_name')->nullable();
            $table->string('area')->nullable();

            $table->json('tracking_status')->nullable();  // تخزين الحالة كـ JSON

                $table->string('certificate_path')->nullable();


            $table->text('notes')->nullable();
            $table->string('inspector_name')->nullable();
            $table->string('gis_name')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking_certificates');
    }
};
