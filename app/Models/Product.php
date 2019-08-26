<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Product extends Model {

	use Attachable, Notifiable, Filterable, AsSource;
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $primaryKey = 'id'; // or null
	public $incrementing = true;
	protected $casts = [
		'properties' => 'array',
	];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'type', 'status', 'sku', 'cat_id', 'tax_id', 'sellable', 'consumable', 'allow_assistant', 'discount', 'stockable', 'variants', 'composites', 'commission_id', 'properties', 'note',
	];

	protected $allowedFilters = ['name', 'type', 'sku', 'category', 'status'];
	protected $allowedSorts = ['name', 'type', 'sku', 'category', 'status'];

	public function __construct(array $attributes = []) {
		$id = Auth::id();
		$this->table = "user_{$id}_" . $this->getTable();
		parent::__construct($attributes);
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function tax() {
		return $this->belongsTo('App\Models\Setting', 'tax_id');
	}

	public function category() {
		return $this->belongsTo('App\Models\Setting', 'cat_id');
	}

	public function commission() {
		return $this->belongsTo('App\Models\Setting', 'commission_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}
