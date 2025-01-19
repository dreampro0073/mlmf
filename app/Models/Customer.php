<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable {

    use Notifiable;

    protected $table = 'customers';

    public static function getCustomerId($enc_id){
        $client  = DB::table('customers')->select('id')->where("client_id", Auth::user()->client_id)->where('enc_id', $enc_id)->first();
        if(!$client){die('Data Not Found!');}
        return $client->id;
    }
}
