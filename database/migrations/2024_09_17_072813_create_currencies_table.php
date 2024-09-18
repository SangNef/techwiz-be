<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->decimal('exchange_rate', 15, 6);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        DB::table('currencies')->insert([
            [
                'name' => 'Việt Nam Đồng',
                'code' => 'VND',
                'exchange_rate' => 1.000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'exchange_rate' => 23000.000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'exchange_rate' => 27000.000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Japanese Yen',
                'code' => 'JPY',
                'exchange_rate' => 210.000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'British Pound',
                'code' => 'GBP',
                'exchange_rate' => 32000.000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
