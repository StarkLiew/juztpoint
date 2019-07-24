<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable {
	use SoftDeletes, Notifiable;

}
