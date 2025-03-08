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
        Schema::create('tyreperformanceinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tyre_site_id')->nullable();
            $table->unsignedBigInteger('tfr_id')->comment('Tyre-Fitman-Removel-id')->nullable();
            $table->double('rtd_a')->comment('Remaining-Thread-Depth')->nullable();
            $table->double('rtd_b')->comment('Remaining-Thread-Depth')->nullable();
            $table->double('current_hmr')->nullable();
            $table->double('lbsr')->comment('Log-Book-Sap-Reading')->nullable();
            $table->double('hcicm')->comment('Hours-Covered-In-Current-Machine')->nullable();
            $table->date('service_date')->nullable();
            $table->double('fl')->comment('Front-Life')->nullable();
            $table->double('rl')->comment('Rear-Life')->nullable();
            $table->double('repaire_life')->nullable();
            $table->integer('remark')->nullable();
            $table->unsignedBigInteger('operatorid')->nullable();
            $table->timestamps();

            // add foreign key
            $table->foreign('tyre_site_id')->references('id')->on('tyresiteinfos')->onDelete('cascade');
            $table->foreign('tfr_id')->references('id')->on('tyrefitmanremovalinfos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyreperformanceinfos');
    }
};
