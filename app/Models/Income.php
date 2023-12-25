<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Income extends Authenticatable {

    use Notifiable;

    protected $table = 'incomes';
        
}


