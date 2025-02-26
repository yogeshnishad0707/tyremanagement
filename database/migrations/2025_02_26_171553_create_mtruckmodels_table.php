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
        Schema::create('mtruckmodels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('truckmake_id')->nullable();
            $table->string('category_name')->nullable();
            $table->enum('status',['0','1'])->default('1');
            $table->unsignedBigInteger('operatorid')->nullable();
            $table->timestamps();

            // adding foregin key
            $table->foreign('truckmake_id')->references('id')->on('mtruckmakes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtruckmodels');
    }
};
