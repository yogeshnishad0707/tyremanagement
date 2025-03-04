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
        Schema::create('repairinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tyre_site_id')->nullable();
            $table->date('service_date')->nullable();
            $table->string('no_of_cut')->nullable();
            $table->string('rtd')->comment('Remaining_Thread_Depth')->nullable();
            $table->string('otl')->comment('Original_Tyre_Life')->nullable();
            $table->string('rl')->comment('Repaired_Life')->nullable();
            $table->string('ttl')->comment('Total_Tyre_Life')->nullable();
            $table->string('patch_no')->nullable();
            $table->string('tyre_status')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedInteger('operatorid')->nullable();
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
        Schema::dropIfExists('repairinfos');
    }
};
