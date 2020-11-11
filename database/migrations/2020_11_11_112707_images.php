<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Images extends Migration
{
    private $table_name = 'images';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('alt');
            $table->string('link');
            $table->string('url');
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
