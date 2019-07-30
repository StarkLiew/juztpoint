<?php

namespace App\Observers;
use Artisan;
use DB;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Tenanti\Observer;

class UserObserver extends Observer {
	public function getDriverName() {
		return 'user';
	}
	/**
	 * Run on created observer.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $entity
	 *
	 * @return bool
	 */
	public function created(Model $entity) {
		//$this->createTenantDatabase($entity);

		if (empty($entity['tenant'])) {
			// Log::debug(json_encode($entity));
			$this->createTenantTable($entity);
			parent::created($entity);
			$this->seed($entity);

		}

	}

	/**
	 * Create database for entity.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $entity
	 *
	 */
	protected function seed(Model $entity) {

		$id = $entity->getKey();

		$companyInfo = Array('name' => $entity->company_name);
		$storeInfo = Array('name' => 'Default');
		$payments = Array('cash', 'card', 'eWallet', 'other');
		$taxes = Array(
			['name' => '', 'code' => '', 'value' => 0],
			['name' => 'SST', 'code' => 'SST', 'value' => 10],
			['name' => 'GST', 'code' => 'SR', 'value' => 6],
			['name' => 'GST', 'code' => 'ZR', 'value' => 0],
		);

		$settings = Array([
			'name' => "Company Setup",
			'description' => "Setup Company Information",
			'type' => "company",
			'properties' => json_encode($companyInfo),
			'user_id' => $id,
		], [
			'name' => "Store Setup",
			'description' => "Setup Store Information",
			'type' => "store",
			'properties' => json_encode($storeInfo),
			'user_id' => $id,
		], [
			'name' => "Payment Type Setup",
			'description' => "Setup Payment Type",
			'type' => "payment",
			'properties' => json_encode($payments),
			'user_id' => $id,
		], [
			'name' => "Tax Setup",
			'description' => "Setup Tax",
			'type' => "tax",
			'properties' => json_encode($taxes),
			'user_id' => $id,
		]);
		DB::table("user_{$id}_settings")->insert($settings);
	}

	/**
	 * Create database for entity.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $entity
	 *
	 */
	protected function createTenantTable(Model $entity) {

		Artisan::call('tenanti:migrate', array('user', '--force' => true));

	}

	/**
	 * Create database for entity.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $entity
	 *
	 * @return mixed
	 */
	protected function createTenantDatabase(Model $entity) {
		$connection = $entity->getConnection();
		$driver = $connection->getDriverName();
		$id = $entity->getKey();

		switch ($driver) {
		case 'mysql':
			$query = "CREATE DATABASE `user_{$id}`";
			break;
		case 'pgsql':
			$query = "CREATE DATABASE `user_{$id}`";
			break;
		default:
			throw new InvalidArgumentException("Database Driver [{$driver}] not supported");
		}
		return $connection->unprepared($query);

	}
}