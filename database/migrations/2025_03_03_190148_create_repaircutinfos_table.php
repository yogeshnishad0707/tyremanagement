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
        Schema::create('repaircutinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repairid')->comment('Repair_Info')->nullable();
            $table->unsignedBigInteger('cl_id')->comment('Cut_Location')->nullable();
            $table->unsignedBigInteger('al_id')->comment('Accurate_Location')->nullable();
            $table->unsignedBigInteger('ntc_id')->comment('Ntc_&_Tc_Cut')->nullable();
            $table->string('cut_lenght')->nullable();
            $table->timestamps();

            // Add foreign key
            $table->foreign('repairid')->references('id')->on('repairinfos')->onDelete('cascade');
            $table->foreign('cl_id')->references('id')->on('mcutlocations')->onDelete('cascade');
            $table->foreign('al_id')->references('id')->on('maccuratelocations')->onDelete('cascade');
            $table->foreign('ntc_id')->references('id')->on('mntccuts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repaircutinfos');
    }
};
