<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id');
            $table->foreign('sample_id')->references('id')->on('samples');
            $table->string('name');
            $table->decimal('budget', 15, 3);
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
        Schema::dropIfExists('sample_categories');
    }
}
