<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Document extends Model {

	use Notifiable, Filterable, AsSource, SoftDeletes;
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
		'reference', 'account_id', 'terminal_id', 'shift_id', 'transact_by', 'date', 'type', 'status', 'discount', 'discount_amount', 'service_charge', 'tax_amount', 'rounding', 'charge', 'refund', 'received', 'change', 'note', 'properties',
	];

	protected $allowedFilters = ['reference', 'status'];
	protected $allowedSorts = ['reference', 'status'];

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

	public function items() {
		return $this->hasMany('App\Models\Item', 'trxn_id');
	}
	public function payments() {
		return $this->hasMany('App\Models\Item', 'trxn_id');
	}
	public function commissions() {
		return $this->hasMany('App\Models\Item', 'trxn_id');
	}

	public function terminal() {
		return $this->belongsTo('App\Models\Setting', 'terminal_id');
	}

	public function account() {
		return $this->belongsTo('App\Models\Account', 'account_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'transact_by');
	}

}
