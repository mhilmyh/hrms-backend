<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employees extends Migration
{
    private $table_name = 'employees';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('mid_name');
            $table->string('last_name');
            $table->string('phone');
            $table->enum('gender', ['M', 'F', 'U']);
            $table->date('birthday');
            $table->bigInteger('salary');
            $table->string('job_position');
            $table->float('rating');
            $table->foreignId('user_id');
            $table->foreignId('image_id')->nullable();
            $table->foreignId('address_id')->nullable();
            $table->foreignId('supervisor_id')->nullable();
            $table->foreignId('department_id')->nullable();
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
