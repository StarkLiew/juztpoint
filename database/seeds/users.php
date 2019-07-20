<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class users extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		DB::table('users')->insert([
			'name' => "System",
			'initial' => "",
			'email' => 'system@salun.pos',
			'password' => bcrypt('secret'),
			'pin' => '',
			'cashier' => false,
			'backoffice' => false,
			'level' => '0',
			'deleted_at' => Carbon::today(),
		]);

		DB::table('users')->insert([
			'name' => "Admin",
			'initial' => "A",
			'email' => 'admin@salun.pos',
			'password' => bcrypt('secret'),
			'pin' => '0000',
			'cashier' => false,
			'backoffice' => true,
			'level' => '1',
		]);

		DB::table('users')->insert([
			'name' => "Cashier",
			'initial' => "C",
			'email' => 'cashier@salun.pos',
			'password' => bcrypt('secret'),
			'pin' => '1111',
			'cashier' => true,
			'backoffice' => false,
			'level' => '2',

		]);

	}
}
