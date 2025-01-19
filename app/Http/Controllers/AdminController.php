<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\Plan;

class AdminController extends Controller {

    public function test(){
        $plan = new \StdClass;
        $day_p = "+14 days";
        $plan->start_date = "2023-10-10";
        $plan->principal_amount = 20000;
        $plan->interest_rate = 22;
        $plan->no_of_emis = 12;
        $dates = [];

        $start_date = $plan->start_date;


        for ($i=0; $i < $plan->no_of_emis; $i++) {

            $g_date = date("Y-m-d",strtotime($start_date." ".$day_p));

            $dates[] = $g_date;
            $start_date = $g_date;
        }

        
        $principal = $plan->principal_amount;
        $annual_interest_rate = $plan->interest_rate;
        $loan_tenure_months = $plan->no_of_emis;
        $monthly_interest_rate = ($annual_interest_rate / 12) / 100;
        $emi_numerator = $principal * $monthly_interest_rate * pow((1 + $monthly_interest_rate), $loan_tenure_months);
        $emi_denominator = pow((1 + $monthly_interest_rate), $loan_tenure_months) - 1;
        $emi = $emi_numerator / $emi_denominator;
        $outstanding_balance = $principal;

        foreach ($dates as $key => $date) {
            $monthly_interest_payment = $outstanding_balance * $monthly_interest_rate;
            $monthly_principal_payment = $emi - $monthly_interest_payment;
            $outstanding_balance -= $monthly_principal_payment;

            $mp = $emi - $monthly_interest_payment;

            echo $date."  -  ".round($emi)."  -  ".round($monthly_interest_payment)."  -  ".round($outstanding_balance)." - ".$mp."<br>";
        }

    }

	public function dashboard(Request $request){

		$group_ids = DB::table('groups')->where("groups.client_id", Auth::user()->client_id)->pluck("id")->toArray();;
        $groups = DB::table('groups')->where("client_id", Auth::user()->client_id)->where('status', 1)->count();
        $clients = DB::table('customers')->where("client_id", Auth::user()->client_id)->where('processing_status', 1)->where('status', 1)->count();
        $plans = DB::table('plans')->where("client_id", Auth::user()->client_id)->where('status', 1)->count();
        $today_groups = DB::table('group_emi_dates')->whereIn("group_id", $group_ids)->where('emi_date', date('Y-m-d'))->get();

        $collection = Plan::collection($today_groups);
        $today_target = $collection['today_target'];
        $today_collection = $collection['total_collection'];
        

		return view('admin.dashboard', [
            "sidebar" => "dashboard",
            "subsidebar" => "dashboard",
            'groups'=>$groups,
            'clients'=>$clients,
            'plans'=>$plans,
            'today_target'=>$today_target,
            'today_collection'=>$today_collection,
        ]);
	}

	public function uploadFile(Request $request){
        $destination = 'uploads/';
        
        if($request->media){
            $file = $request->media;
            $extension = $request->media->getClientOriginalExtension();
            if(in_array($extension, User::fileExtensions())){
                $name = strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);
                $data["media"] = $destination.$name;

                $data["success"] = true;
                $data["media_link"] = url($destination.$name);
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());
    }

	public function pendingList(){

        $toDay = date('Y-m-d');
 
        $pending_list = DB::table('group_emi_dates')->select('group_emi_dates.emi_date','group_emi_dates.emi_amount','groups.group_name','emi_collection.id as emi_collection_id','customers.name as customer_name','customers.mobile','emi_collection.group_id','customers.enc_id')->join('groups','groups.id','=','group_emi_dates.group_id')->leftjoin('emi_collection','emi_collection.group_emi_date_id','=','group_emi_dates.id')->leftjoin('customers','customers.id','=','emi_collection.customer_id')->where('group_emi_dates.emi_date','<',$toDay)->where("groups.client_id", Auth::user()->client_id)->where('groups.active',1)->whereNull('emi_collection.collected_amount')->get();

        $data["success"] = true;
        $data["pending_list"] = $pending_list;
        return Response::json($data, 200, array()); 
    }

    public function deleteGroup($group_id){
        
        $check = DB::table('groups')->where("groups.client_id", Auth::user()->client_id)->where('id', $group_id)->first();
        if($check){
            DB::table('groups')->where('id', $group_id)->delete();
            DB::table('emi_collection')->where('group_id', $group_id)->delete();
            DB::table('group_customers')->where('group_id', $group_id)->delete();
            DB::table('group_emi_dates')->where('group_id', $group_id)->delete();
        }

        return "done";
    }
  

}