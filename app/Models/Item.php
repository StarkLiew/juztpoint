<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Item extends Model {

	use Notifiable, Filterable, AsSource;
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
		'line', 'type', 'trxn_id', 'item_id', 'discount', 'discount_amount', 'refund_amount', 'tax_id', 'tax_amount', 'tax_amount', 'total_amount', 'user_id', 'note', 'user_id',
	];

	protected $allowedFilters = ['line'];
	protected $allowedSorts = ['line'];

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

	public function document() {
		return $this->belongsTo('App\Models\Document', 'trxn_id');
	}

	public function product() {
		return $this->belongsTo('App\Models\Product', 'item_id');
	}
	public function commission() {
		return $this->belongsTo('App\Models\Setting', 'item_id');
	}
	public function payment() {
		return $this->belongsTo('App\Models\Setting', 'item_id');
	}
	public function tax() {
		return $this->belongsTo('App\Models\Setting', 'tax_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}
