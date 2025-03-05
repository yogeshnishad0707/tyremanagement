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
        Schema::table('tyreinformations', function (Blueprint $table) {
            // Add new column make_id and set up foreign key
            $table->unsignedBigInteger('make_id')->nullable();

            $table->foreign('make_id')->references('id')->on('mmakes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tyreinformations', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['make_id']);
            // Drop the column make_id
            $table->dropColumn('make_id');
        });
    }
};
