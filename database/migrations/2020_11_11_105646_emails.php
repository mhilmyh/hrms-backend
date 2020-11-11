<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Emails extends Migration
{
    private $table_name = 'emails';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->longText('message');
            $table->foreignId('sender_id');
            $table->foreignId('receiver_id');
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
