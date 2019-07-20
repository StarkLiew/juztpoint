<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Controller;

class commission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//DB::table('commission_rates')->truncate();
        DB::table('commission_rates')->insert([
            //'id' => $this->action('GET', 'Controller@createObjectId'),

            'name' => "Product",
            'fix' => false,
            'rate' => 10.0,
            'user_id' => 1,

        ]);
        DB::table('commission_rates')->insert([
            //'id' => $this->action('GET', 'Controller@createObjectId'),

            'name' => "Service",
            'fix' => true,
            'rate' => 1.00,
            'user_id' => 1,

        ]);
         DB::table('commission_rates')->insert([
            //'id' => $this->action('GET', 'Controller@createObjectId'),

            'name' => "Addon",
            'fix' => true,
            'rate' => 0.50,
            'user_id' => 1,

        ]);
    }
}
