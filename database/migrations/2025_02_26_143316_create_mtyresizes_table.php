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
        Schema::create('mtyresizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tyretype_id')->nullable(); 
            $table->string('category_name')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->unsignedBigInteger('operatorid')->nullable();
            $table->timestamps();

            // Adding foreign key constraint, assuming 'tyretypes' table exists
            $table->foreign('tyretype_id')->references('id')->on('mtyretypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtyresizes');
    }
};
