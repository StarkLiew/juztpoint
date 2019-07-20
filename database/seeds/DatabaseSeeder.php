<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//    DB::table('users')->truncate();
		//        DB::table('accounts')->truncate();
		// DB::table('system_lockers')->truncate();
		$this->call('users');
		$this->call('access_control');
		$this->call('OAuthClientSeeder');
		$this->call('countries');

	}

}
