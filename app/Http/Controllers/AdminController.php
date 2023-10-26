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
            


            // dd($monthly_interest_payment);

            echo $date."  -  ".round($emi)."  -  ".round($monthly_interest_payment)."  -  ".round($outstanding_balance)." - ".$mp."<br>";

            // $s_dt = [
            //     'group_id' => $group_id,
            //     'emi_date' => $date,
            //     'emi_amount' => round($emi),
            //     'interest_payment' => round($monthly_interest_payment,1),
            //     'principal_payment' => round($outstanding_balance),
            // ];

            // $date_check = DB::table('group_emi_dates')->where('group_id',$group_id)->where("emi_date",$date)->first();

            // if(!$date_check){
            //     DB::table('group_emi_dates')->insert($s_dt);    
            // }
        }

    }

	public function dashboard(Request $request){

		$groups = DB::table('groups')->where('status', 1)->count();
        $clients = DB::table('customers')->where('status', 1)->count();
        $plans = DB::table('plans')->where('status', 1)->count();
        $today_groups = DB::table('group_emi_dates')->where('emi_date', date('Y-m-d'))->get();
        // $collected_groups = DB::table('group_emi_dates')->where('emi_date', date('Y-m-d'))->where('emi_status', 1)->get();

        // dd($today_groups);

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
       
        $pending_list = DB::table('emi_collection')->select('customers.name as customer_name', 'customers.mobile','group_emi_dates.emi_date','groups.group_name','emi_collection.id as emi_collection_id','group_emi_dates.emi_amount')->leftJoin('customers','customers.id','=','emi_collection.customer_id')->leftJoin('group_emi_dates','group_emi_dates.id','emi_collection.group_emi_date_id')->leftJoin('groups','groups.id','=','emi_collection.group_id')->where('group_emi_dates.emi_date','<',$toDay)->whereNull('collected_amount')->get();


        $data["success"] = true;
        $data["pending_list"] = $pending_list;
        return Response::json($data, 200, array()); 
    }
  

}