<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupDefaultTables2022 extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('interswitch_payments')) {
            DB::unprepared(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'database.sqlite'));
        }
    }

    public function down()
    {
        //
    }
}
