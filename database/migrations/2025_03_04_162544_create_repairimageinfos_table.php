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
        Schema::create('repairimageinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repair_id')->nullable();
            $table->string('image')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();

            // Add foreign key
            $table->foreign('repair_id')->references('id')->on('repairinfos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairimageinfos');
    }
};
