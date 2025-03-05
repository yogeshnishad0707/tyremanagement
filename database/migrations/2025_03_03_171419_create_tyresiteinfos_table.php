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
        Schema::create('tyresiteinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('truck_modal_id')->nullable();
            $table->unsignedBigInteger('tyre_info_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->string('ponumber')->nullable();
            $table->string('truck_no')->nullable();
            $table->double('otl')->comment('original tyre life')->nullable();
            $table->date('fitmandate')->nullable();
            $table->date('removaldate')->nullable();
            $table->date('replacedate')->nullable();
            $table->double('front_life')->nullable();
            $table->double('rear_life')->nullable();
            $table->double('repair_life')->nullable();
            $table->string('current_status')->nullable();
            $table->string('remark')->nullable();
            $table->enum('status',['0','1'])->default();
            $table->string('operatorid')->nullable();
            $table->timestamps();

            // add foreign key
            $table->foreign('project_id')->references('id')->on('siteprojects')->onDelete('cascade');
            $table->foreign('truck_modal_id')->references('id')->on('mtruckmodels')->onDelete('cascade');
            $table->foreign('tyre_info_id')->references('id')->on('tyreinformations')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('mtyrepositions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyresiteinfos');
    }
};
