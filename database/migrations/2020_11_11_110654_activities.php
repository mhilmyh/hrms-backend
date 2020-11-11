<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Activities extends Migration
{
    private $table_name = 'activities';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('desc');
            $table->time('start_time');
            $table->time('stop_time');
            $table->foreignId('timesheet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table_name);
    }
}
