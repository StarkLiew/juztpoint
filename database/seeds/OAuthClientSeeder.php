<?php

// database/seeds/OAuthClientSeeder.php

use Illuminate\Database\Seeder;

class OAuthClientSeeder extends Seeder {

    public function run(){

      //  DB::table('oauth_clients')->truncate();


        DB::table('oauth_clients')->insert(
                [   'id' => 1,
                    'secret' => "backoffice",
                    'name' => "Back Office",
                    'redirect' => "/",
                    'personal_access_client'=>false,
                    'password_client'=>true,
                    'revoked'=>false,
                ]
            );
        DB::table('oauth_clients')->insert(
                [   'id' => 2,
                    'secret' => "store1registerar1",
                    'name' => "S2 Store Registar 1",
                    'redirect' => "/",
                    'personal_access_client'=>false,
                    'password_client'=>true,
                    'revoked'=>false,
                ]
            );



    }
}