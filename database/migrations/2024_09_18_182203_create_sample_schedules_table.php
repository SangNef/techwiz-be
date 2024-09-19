<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_category_id');
            $table->foreign('sample_category_id')->references('id')->on('sample_categories');
            $table->string('title');
            $table->date('day');
            $table->time('time');
            $table->decimal('amount', 15, 3);
            $table->softDeletes();
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
        Schema::dropIfExists('sample_schedules');
    }
}
