<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->char('name',255);
            $table->time('opening_hours');
            $table->time('closing_hours');
            $table->char('building',255);
            $table->boolean('is_branch');
            $table->unsignedBigInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('offices');
            $table->foreignId('images_id')->constrained('images');
            $table->foreignId('addresses_id')->constrained('addresses');
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
        Schema::dropIfExists('offices');
    }
}
