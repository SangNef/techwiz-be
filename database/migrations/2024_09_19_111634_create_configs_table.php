<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('configs')->insert([
            [
                'key' => 'home_section_1',
                'value' => json_encode([
                    'banner' => [
                        'https://res.cloudinary.com/dx2o9ki2g/image/upload/v1726770287/vjxj04q60amtvtwih0qy.jpg',
                        'https://res.cloudinary.com/dx2o9ki2g/image/upload/v1726770653/jgsapacnq5ca7mvdhbiz.jpg',
                        'https://res.cloudinary.com/dx2o9ki2g/image/upload/v1726770505/m8tqgtuk2noprknyxfoz.jpg',
                    ],
                    'title' => ['Nha Trang in Vietnam', 'Dubai in UAE', 'Tokyo in Japan'],
                ]),
                'description' => 'home'
            ],
            [
                'key' => 'home_section_2',
                'value' => json_encode([
                    'title' => ['500+ Destinations', 'Best Price Guarantee', '24/7 Customer Support'],
                    'description' => ['Travel to over 500 destinations worldwide', 'We offer the best price guarantee', 'Our customer support team is available 24/7'],
                ]),
                'description' => 'home'
            ],
            [
                'key' => 'home_section_3',
                'value' => json_encode([
                    'image' => 'https://thorindustries-prod.zaneray.com/cms/images/e0e0af86-ce3b-4eb1-900b-97a2bb40bb8a__CEM1232.jpg?auto=compress,format&rect=0,2111,3413,1422&w=1920&h=800',
                    'title' => 'Easily plan your itinerary and manage finances for your trip.'
                ]),
                'description' => 'home'
            ],
            [
                'key' => 'home_section_4',
                'value' => json_encode([
                    'title' => 'Why Choose Us?',
                    'content' => [
                        '40,000+ customers',
                        '500+ destinations',
                        'Award-winning service',
                    ],
                    'description' => [
                        'We have served over 40,000 customers worldwide',
                        'Travel to over 500 destinations worldwide',
                        'We have won numerous awards for our service',
                    ],
                ]),
                'description' => 'home'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
