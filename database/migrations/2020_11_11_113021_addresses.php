<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addresses extends Migration
{
    private $table_name = 'addresses';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('subdistrict');
            $table->string('postal_code');
            $table->string('street');
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
