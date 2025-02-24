<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adding columns
            $table->unsignedBigInteger('role_id')->after('id')->nullable();
            $table->unsignedBigInteger('parent_id')->after('role_id')->nullable();
            $table->string('mobile_no', 10)->after('email')->nullable();
            $table->text('address')->after('mobile_no')->nullable();

            // Adding foreign key constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping foreign key and columns
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'parent_id', 'mobile_no', 'address']);
        });
    }
};