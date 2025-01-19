<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Plan extends Authenticatable {

    use Notifiable;

    protected $table = 'plans';

   
    public static function collection ($today_groups){
        $today_target = 0;
        $total_collection = 0;


        foreach ($today_groups as $key => $item) {
            $customer_count = DB::table('group_customers')->where("client_id", Auth::user()->client_id)->where('group_id',$item->group_id)->count();
            $today_target = $today_target + ($customer_count*$item->emi_amount);

            $emi_collection_count = DB::table('emi_collection')->where('group_id',$item->group_id)->where('group_emi_date_id',$item->id)->where("client_id", Auth::user()->client_id)->where('collected_amount',1)->count();

            // dd($emi_collection_count);

            $total_collection = $total_collection + ($item->emi_amount*$emi_collection_count);
        }

        return [
            'today_target' => $today_target,
            'total_collection' => $total_collection,
        ];
    }
        
}
