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
        Schema::create('tyreinformations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tyresize_id')->nullable();
            // $table->string('make')->nullable();
            $table->string('tyre_no')->unique()->nullable();
            $table->string('current_status')->nullable();
            $table->double('otl')->comment('original tyre life')->nullable();
            $table->double('otd')->comment('original thread dept')->nullable();
            $table->enum('status',['0','1'])->default('1');
            $table->unsignedBigInteger('operatorid')->nullable();
            $table->timestamps();

            // add foreign key 
            $table->foreign('tyresize_id')->references('id')->on('mtyresizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyreinformations');
    }
};
