<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Setting extends Model {

	use Notifiable, Filterable, AsSource;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */

	protected $dates = ['deleted_at'];
	protected $primaryKey = 'id'; // or null\
	protected $casts = [
		'properties' => 'array',
	];

	/**
	 * @var
	 */
	protected $allowedFilters = ['name'];
	protected $allowedSorts = ['name'];

	public $incrementing = true;

	public function __construct(array $attributes = []) {
		$id = Auth::id();
		$this->table = "user_{$id}_" . $this->getTable();
		parent::__construct($attributes);
	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description', 'type', 'data',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}
