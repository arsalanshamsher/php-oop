<?php

namespace App\Core\Database;

abstract class Migration
{
    /**
     * Run the migrations.
     */
    abstract public function up();

    /**
     * Reverse the migrations.
     */
    abstract public function down();
}
