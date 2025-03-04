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
        Schema::create('tyrefitmanremovalinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tyre_site_id')->nullable();
            $table->string('type')->nullable();
            $table->date('service_date')->nullable();
            $table->string('lbsr')->comment('log_book_sap_reading')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();

            // Add foreign key
            $table->foreign('tyre_site_id')->references('id')->on('tyresiteinfos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyrefitmanremovalinfos');
    }
};
