<?php

use App\Database\Migration;
use App\Database\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('roles', function ($table) {
            $table->string('description')->nullable()->after('name');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('roles', function ($table) {
            $table->dropColumn('status');
            $table->dropColumn('description');
        });
    }

};
