<?php namespace App\Observers;
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
		$this->createTenantDatabase($entity);
		parent::created($entity);
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
			$query = "CREATE DATABASE user_{$id}";
			break;
		default:
			throw new InvalidArgumentException("Database Driver [{$driver}] not supported");
		}
		return $connection->unprepared($query);
	}
}