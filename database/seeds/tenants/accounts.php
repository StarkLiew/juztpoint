<?php

use Illuminate\Database\Seeder;

class accounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	  

        $account_id = DB::table('accounts')->insertGetId([
            'name' => "Walk-in",
            'supplier' => false,
            'email' => "walkin@salun.app",
            'registerid' => 'WALKIN',
            'user_id' => 1
        ]);
        DB::table('system_lockers')->insert([
            'item_id' =>  $account_id,
            'item_table' => "accounts",
            'user_id' => 1
        ]);


        DB::table('products')->insertGetId([
            'name' => "Service",
            'service' => true,
            'commission_id' => 1,
            'cat_id' => 1,
            'user_id' => 1
        ]);
    }
        
}
