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

		$companyInfo = Array(
			'address' => null,
			'timezone' => "Asia\/Kuala_Lumpur",
			'currency' => "MYR",
		);
		$storeInfo = Array(
			'timezone' => "Asia\/Kuala_Lumpur",
			'currency' => "MYR",
		);

		$tax1 = Array(
			'name' => 'No Tax',
			'description' => 'No Tax',
			'type' => 'tax',
			'properties' => json_encode(Array("code" => "", "rate" => 0.00)),
			'user_id' => $id,
		);
		$tax2 = Array(
			'name' => 'SST',
			'description' => 'Sale & Service Tax',
			'type' => 'tax',
			'properties' => json_encode(Array("code" => "SST", "rate" => 10.00)),
			'user_id' => $id,
		);
		$tax3 = Array(
			'name' => 'GST SR',
			'description' => 'Good & Service Tax Standard Rate',
			'type' => 'tax',
			'properties' => json_encode(Array("code" => "SR", "rate" => 6.00)),
			'user_id' => $id,
		);
		$tax4 = Array(
			'name' => 'GST ZR',
			'description' => 'Good & Service Tax Zero Rate',
			'type' => 'tax',
			'properties' => json_encode(Array("code" => "ZR", "rate" => 0.00)),
			'user_id' => $id,
		);

		$commission = Array(
			'name' => 'Default',
			'description' => 'Default 10% commission rate',
			'type' => 'commission',
			'properties' => json_encode(Array("rate" => 10.00, "type" => 0)),
			'user_id' => $id,
		);
		$service = Array(
			'name' => 'Default',
			'description' => 'Default 10% service charge',
			'type' => 'service',
			'properties' => json_encode(Array("rate" => 10.00, "type" => 0)),
			'user_id' => $id,
		);

		$category = Array(
			'name' => 'Default',
			'description' => null,
			'type' => 'category',
			'properties' => null,
			'user_id' => $id,
		);

		$payment1 = Array(
			'name' => 'Cash',
			'description' => null,
			'type' => 'payment',
			'properties' => null,
			'user_id' => $id,
		);
		$payment2 = Array(
			'name' => 'Card',
			'description' => null,
			'type' => 'payment',
			'properties' => null,
			'user_id' => $id,
		);

		$settings = Array([
			'name' => $entity->company_name,
			'description' => "About my awesome company.",
			'type' => "company",
			'properties' => json_encode($companyInfo),
			'user_id' => $id,
		], [
			'name' => "Default Store",
			'description' => "Default Store",
			'type' => "store",
			'properties' => json_encode($storeInfo),
			'user_id' => $id,
		], $tax1, $tax2, $tax3, $tax4, $payment1, $payment2, $category, $commission, $service,
		);

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