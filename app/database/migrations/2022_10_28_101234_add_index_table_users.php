<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('DROP FULLTEXT INDEX ON users');
        DB::statement('ALTER TABLE users ADD FULLTEXT search1(name,email)');
        DB::statement('ALTER TABLE roles ADD FULLTEXT search(slug)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE users DROP INDEX search1');
        DB::statement('ALTER TABLE roles DROP INDEX search');
    }
};
