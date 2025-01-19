<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Plan;

class PlanController extends Controller {

	public function index(Request $request){

		$plans = DB::table("plans")->select('plans.*', 'emi_types.type_name')->leftjoin('emi_types', 'emi_types.id', '=', 'plans.emi_type')->where('status', 1)->where("plans.client_id", Auth::user()->client_id);
		if($request->emi_type){
			$plans = $plans->where('emi_type', $request->emi_type);
		}

		if($request->principal_amount){
			$plans = $plans->where('principal_amount', $request->principal_amount);
		}
		
		if($request->plan_name){
			$plans = $plans->where('plan_name', 'LIKE', '%'.$request->plan_name.'%');
		}


		$plans = $plans->get();
		// $final_plans = [];

		foreach ($plans as $key => $plan) {
			$plan->editable = true;
			$count = DB::table('groups')->where("client_id", Auth::user()->client_id)->where('plan_id',$plan->id)->where('active',1)->count();

			if($count > 0){
				$plan->editable = false;
			}
		}

		
		return view('admin.plans.index', [
            "sidebar" => "plans",
            "subsidebar" => "plans",
            "plans" => $plans,
        ]);
	}

	public function addPlan($plan_id = 0){
		return view('admin.plans.add', [
            "sidebar" => "entry",
            "subsidebar" => "entry",
            'plan_id' => $plan_id,
        ]);
	}


	public function planInit(Request $request){

		$plan = DB::table('plans')->where('id', $request->plan_id)->where("client_id", Auth::user()->client_id)->first();
		$emi_types = DB::table('emi_types')->where('for_user', 0)->select('id', 'type_name')->get();

		$data['success'] = true;
		$data['plan'] = $plan;
		$data['emi_types'] = $emi_types;

		return Response::json($data, 200, []);
	}	
	
	public function viewPlan(Request $request){

		$plan = DB::table('plans')->select('plans.*', 'emi_types.type_name')->leftjoin('emi_types', 'emi_types.id', '=', 'plans.emi_type')->where("plans.client_id", Auth::user()->client_id)->where('plans.id', $request->plan_id)->first();

		$data['success'] = true;
		$data['plan'] = $plan;

		return Response::json($data, 200, []);
	}	

	public function planStore(Request $request){

		$cre = [
			'plan_name'=>$request->plan_name,
			'principal_amount'=>$request->principal_amount,
			'interest_rate'=>$request->interest_rate,
			'time_line'=>$request->time_line,
			'emi_type'=>$request->emi_type,
		];

		$rules = [
			'plan_name'=>'required',
			'principal_amount'=>'required',
			'interest_rate'=>'required',
			'time_line'=>'required',
			'emi_type'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			$emi_values = [];

			if($request->emi_type == 1){
				
				$cal = $this->emiCalculator($request);
				$emi_amount = round($cal['emi'] / 28, 2);
				$no_of_emis = $cal['loan_tenure_months'] * 28 ;
			}
			
			if($request->emi_type == 3){
				$cal = $this->emiCalculator($request);
				$emi_amount = round($cal['emi'] / 4, 2);
				$no_of_emis = $cal['loan_tenure_months'] * 4 ;
			}

			if($request->emi_type == 4){
				$cal = $this->emiCalculator($request);
				$emi_amount = round($cal['emi'] / 2, 2);
				$no_of_emis = $cal['loan_tenure_months'] * 2 ;
			}
			
			if($request->emi_type == 5){
				$cal = $this->emiCalculator($request);
				$emi_amount = round($cal['emi'], 2);
				$no_of_emis = $cal['loan_tenure_months'];
			}
			// $total_amount = round($emi_amount * $no_of_emis, 2);
			// $interest_amount = $total_amount - $request->principal_amount;




			$data= [
				'principal_amount'=>$request->principal_amount,
				'interest_rate'=>$request->interest_rate,
				'plan_name'=>$request->plan_name,
				'time_line'=>$request->time_line,
				'emi_type'=>$request->emi_type,
				'no_of_emis'=>$no_of_emis,
				// 'emi_amount'=>$emi_amount,
				// 'total_amount'=>$total_amount,
				// 'interest_amount'=>$interest_amount,
			];

			$principal = $request->principal_amount;
			$annual_interest_rate = $request->interest_rate;
			$loan_tenure_months = $no_of_emis;

			$monthly_interest_rate = ($annual_interest_rate / 12) / 100;
			$emi_numerator = $principal * $monthly_interest_rate * pow((1 + $monthly_interest_rate), $loan_tenure_months);
			$emi_denominator = pow((1 + $monthly_interest_rate), $loan_tenure_months) - 1;
			$emi = $emi_numerator / $emi_denominator;

			$data['emi_amount'] = $emi;



			if($request->id){
				DB::table('plans')->where("client_id", Auth::user()->client_id)->where('id', $request->id)->update($data);
				$message = "Updated Successfully!";
			} else {
				$data['created_at'] = date('Y-m-d H:i:s');
				$data["client_id"] = Auth::user()->client_id;
				DB::table('plans')->insert($data);
				$message = "Stored Successfully!";
			}

			$data['message'] = $message;
			$data['success'] = true;
			$data['redirect_url'] = url('admin/plans');
		}else{
			$data['message'] = $validator->errors();
			$data['success'] = false;
		}


		return Response::json($data, 200, []);
	}
	
	public function deletePlan($plan_id){
		DB::table('plans')->where('client_id', $client_id)->where('id', $plan_id)->update([
			'status' => 0, 
		]);
		return Redirect::back()->with('success', "Delete Successfully");
	}

	public function emiCalculator($request){
		// $principal = $request->principal_amount;  
		// $annual_interest_rate = $request->interest_rate;  
		// $loan_tenure_months = $request->time_line / 28;
		// $monthly_interest_rate = ($annual_interest_rate / 12) / 100;
		// $emi_numerator = $principal * $monthly_interest_rate * pow((1 + $monthly_interest_rate), $loan_tenure_months);
		// $emi_denominator = pow((1 + $monthly_interest_rate), $loan_tenure_months) - 1;
		// $emi = $emi_numerator / $emi_denominator;
		// $emi = $emi;

		$principal = $request->principal_amount;
		$annual_interest_rate = $request->interest_rate;
		$loan_tenure_months = $request->time_line / 30;
		$monthly_interest_rate = ($annual_interest_rate / 12) / 100;
		$emi_numerator = $principal * $monthly_interest_rate * pow((1 + $monthly_interest_rate), $loan_tenure_months);
		$emi_denominator = pow((1 + $monthly_interest_rate), $loan_tenure_months) - 1;
		$emi = $emi_numerator / $emi_denominator;

		$cal['emi'] = $emi;
		$cal['loan_tenure_months'] = $loan_tenure_months;
		return $cal;
	}

	function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

}


