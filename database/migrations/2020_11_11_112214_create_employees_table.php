<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->char('first_name', 255);
            $table->char('mid_name', 255);
            $table->char('last_name', 255);
            $table->char('phone', 20);
            $table->enum('gender', ['L', 'P', 'U']);
            $table->date('birhtday');
            $table->bigInteger('salary');
            $table->char('job_position', 255);
            $table->float('rating', 2, 2);
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('images_id')->constrained('images');
            $table->foreignId('addresses_id')->constrained('addresses');
            $table->unsignedBigInteger('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('employees');
            $table->foreignId('departments_id')->constrained('departmens');
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
        Schema::dropIfExists('employees');
    }
}
