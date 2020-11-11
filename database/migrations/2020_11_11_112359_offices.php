<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Offices extends Migration
{
    private $table_name = 'offices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->string('building');
            $table->boolean('is_branch');
            $table->timestamps();
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
