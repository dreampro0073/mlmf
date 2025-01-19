<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Customer,App\Models\Plan;
use Dompdf\Dompdf;
use Dompdf\Options;

class GroupsOldController extends Controller {

	public function addGroup($group_id = 0){
		return view('admin.groups.add_old', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            'group_id' => $group_id,
        ]);
	} 

	public function groupInit(Request $request){
		$customers = DB::table('customers')->where("client_id", Auth::user()->client_id)->select('id','name')->get();
		$group = DB::table('groups')->where("client_id", Auth::user()->client_id)->where('id', $request->group_id)->first();

		if($group){
			
			$group_customers = DB::table('group_customers')->select('customers.id','customers.name')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group->id)->where("client_id", Auth::user()->client_id)->get();

			$group->customers = $group_customers;
			if($group->start_date){
				$group->start_date = date("m/d/Y",strtotime($group->start_date));
				$group->day = (date('d', strtotime($group->start_date)))*1;
			} 
		}

		$blocks = DB::table('blocks')->get();
		$plans = DB::table("plans")->select('id','plan_name')->where("client_id", Auth::user()->client_id)->get();

		$data['success'] = true;
		$data['group'] = $group;
		$data['plans'] = $plans;
		$data['blocks'] = $blocks;
		$data['customers'] = $customers;


		return Response::json($data, 200, []);
	}		


	public function groupStore(Request $request){
		$client_id = Auth::user()->client_id;
		$cre = [
			'group_name'=>$request->group_name,
		];

		$rules = [
			'group_name'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		$start_date = $request->year.'-'.$request->month.'-'.$request->day;

		if($validator->passes()){

			$data= [
				'group_name'=>$request->group_name,
				'plan_id'=>$request->plan_id,
				'block_id'=>$request->block_id,
				'village_id'=>$request->village_id,
				'pin_code'=>$request->pin_code,
				'insurance_fee'=>$request->insurance_fee,
				'processing_fee'=>$request->processing_fee,
				'start_date' => date('Y-m-d', strtotime($start_date)),
				
			];

			if($request->id){
				$group_id = $request->id;
				
				DB::table('groups')->where('id', $request->id)->where("client_id", $client_id)->update($data);
				$message = "Updated Successfully!";
			} else {
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['client_id'] = $client_id;
				$group_id = DB::table('groups')->insertGetId($data);
				$message = "Stored Successfully!";
			}

			$customers = $request->customers;

			$group_customer_ids = [];
			if(sizeof($customers) > 0){
				foreach ($customers as $key => $customer) {
					$check = DB::table('group_customers')->where("client_id", $client_id)->where('group_id',$group_id)->where('customer_id',$customer['id'])->first();

					$g_data = [
						'group_id' =>$group_id,
						'customer_id' => $customer['id'],
					];

					if($check){
						DB::table('group_customers')->where("client_id", $client_id)->where('group_id',$group_id)->where('customer_id',$customer['id'])->update($g_data);
						$group_customer_id = $check->id;
					}else{
						$g_data["client_id"] = $client_id;
						$g_data["created_at"] = date("Y-m-d H:i:s");
						$group_customer_id = DB::table('group_customers')->insertGetId($g_data);
					}

					$group_customer_ids[] = $group_customer_id;
				}

				DB::table('group_customers')->where("client_id", $client_id)->where('group_id',$group_id)->whereNotIn('id',$group_customer_ids)->delete();
			}

			$data['message'] = $message;
			$data['success'] = true;
			$data['group_id'] = $group_id;
			$data['redirect_url'] = url('admin/groups');
		}else{
			$data['message'] = $validator->errors();
			$data['success'] = false;
		}


		return Response::json($data, 200, []);
	}

	public function actvateGroup(Request $request){
		$group_id = $request->group_id;
		$client_id = Auth::user()->client_id;
		$g_check = DB::table("groups")->where("client_id", $client_id)->where("id", $group_id)->first();
		if(!$g_check){
			die("Not Authorised !");
		}

		$plan = DB::table('groups')->select('plans.*','groups.start_date','groups.second_date')->leftjoin('plans','plans.id','=','groups.plan_id')->where("plans.client_id", $client_id)->where('groups.id',$group_id)->first();


		if($plan){
			$dates = [];
				       	
	       	if($plan->emi_type == 5){
	       		$start_date = date("Y-m-d",strtotime("+1 month".$plan->start_date));
	        	
		        $f1_day =  date("d",strtotime($start_date));

		        $f1_mon =  date("m",strtotime($start_date));

		        $y1_year = date("Y",strtotime($start_date));

		        $s_date1 = $start_date;
	       		for ($i=0; $i < $plan->no_of_emis; $i++) { 

	       			$s_date1 = date($y1_year."-".$f1_mon.'-'.$f1_day,strtotime($s_date1));
		            if($f1_mon == 12){
		                $f1_mon = "01";
		                $y1_year++;
		            }else{
		                $f1_mon= date("m",strtotime("+1 month".$s_date1));

		            }  
					$dates[] = $s_date1;
				}
	       	}


	       	if($plan->emi_type == 4){

	       		$start_date = date("Y-m-d",strtotime($plan->start_date));

	       		$fixed_day = date("d",strtotime($start_date));
	        	$second_date = date("Y-m-d",strtotime("+15 days".$start_date));



		        $f1_day =  date("d",strtotime($start_date));
		        $f2_day =  date("d",strtotime($second_date));

		        $f1_mon =  date("m",strtotime($start_date));
		        $f2_mon =  date("m",strtotime($second_date));

		        $y1_year = date("Y",strtotime($start_date));
		        $y2_year = date("Y",strtotime($second_date));

	       		if($fixed_day >= 16){

	       			$second_date = date("Y-m-d",strtotime("-15 days".$start_date));
	       			$f2_day =  date("d",strtotime($second_date));
		            $f2_mon =  date("m",strtotime($second_date));
		            $y2_year = date("Y",strtotime($second_date));
	       			
	       			if($f2_mon == 12){
		                $f2_mon = "01";
		                $y2_year++;

		            }else{
		                $f2_mon= date("m",strtotime("+1 month".$second_date));
		            }

		            $second_date = date('Y-m-d',strtotime($y2_year.'-'.$f2_mon.'-'.$f2_day));

	       		}

		        $s_date1 = $start_date;
		        $s_date2 = $second_date;

		        $active_date = date("Y-m-d",strtotime($plan->updated_at));

	       		for ($i=0; $i < $plan->no_of_emis; $i) { 

		            $s_date1 = date('Y-m-d',strtotime($y1_year.'-'.$f1_mon.'-'.$f1_day));
		            $s_date2 = date('Y-m-d',strtotime($y2_year.'-'.$f2_mon.'-'.$f2_day));

		            if($f1_mon == 12){
		                $f1_mon = "01";
		                $y1_year++;
		            }else{
		                $f1_mon= date("m",strtotime("+1 month".$s_date1));

		            }   
		            if($f2_mon == 12){
		                $f2_mon = "01";
		                $y2_year++;

		            }else{
		                $f2_mon= date("m",strtotime("+1 month".$s_date2));
		            }

		            if($i < $plan->no_of_emis ){
		            	$dates[] = $s_date1;

		            	$i++;
		            }		            

		            if($i < $plan->no_of_emis ){
		            	$dates[] = $s_date2;
		            	$i++;

		            }
		        }	       		       		
	       	}

	       	if($plan->emi_type == 3){

	       		$s_date = date("Y-m-d",strtotime($plan->start_date));

		        $active_date = date("Y-m-d",strtotime($plan->updated_at));

	       		for ($i=0; $i < $plan->no_of_emis; $i++) { 

		            $s_date = date('Y-m-d',strtotime($s_date));
		           	$dates[] = $s_date;
		            $s_date = date("Y-m-d",strtotime("+7 days".$s_date));
		        }	       		       		
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

				$start_m_principal = $outstanding_balance;
				$outstanding_balance -= $monthly_principal_payment;

				$principal_repayment = $emi - $monthly_interest_payment;


				$s_dt = [
					'group_id' => $group_id,
					'emi_date' => $date,
					'emi_amount' => round($emi),
					'interest_payment' => round($monthly_interest_payment,1),
					'principal_payment' => round($outstanding_balance),
					'principal_repayment' => round($principal_repayment),
					'start_m_principal' => round($start_m_principal),
				];

				$date_check = DB::table('group_emi_dates')->where('group_id',$group_id)->where("emi_date",$date)->first();

				if(!$date_check){
					$s_dt["created_at"] = date("Y-m-d H:i:s");
					DB::table('group_emi_dates')->insert($s_dt);	
				}
			}
		}

		$group_dates = DB::table('group_emi_dates')->where('group_id',$group_id)->get();

		$group_customers  = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where("group_customers.client_id", $client_id)->where('group_customers.group_id',$group_id)->get();

		$group_dates = $group_dates;

		$show_customers = [];
		$emi_collection_ids = []; 
		if(sizeof($group_customers) > 0){
			foreach ($group_customers as $key => $group_customer) {
				if(sizeof($group_dates) > 0){
					foreach ($group_dates as $key => $group_date) {
						$check_entry = DB::table('emi_collection')->where('group_id',$group_id)->where("customer_id",$group_customer->customer_id)->where('group_emi_date_id',$group_date->id)->where("client_id", $client_id)->first();

						$entry_data = [
							'group_id' => $group_id,
							'customer_id' => $group_customer->customer_id,
							'group_emi_date_id' => $group_date->id,
							'created_at' => date("Y-m-d H:i:s"),
							'client_id' => $client_id,
						];

						if(!$check_entry){
							$emi_id = DB::table("emi_collection")->insertGetId($entry_data);
							if(date('Y-m-d', strtotime('now')) > date('Y-m-d', strtotime($group_date->emi_date))){
								$emi_collection_ids[] = $emi_id; 
							}
						}
					}
				}
			}
		}

		DB::table('groups')->where('id',$group_id)->where("client_id", $client_id)->update([
			'active' => 1
		]);


		$this->saveCollection($emi_collection_ids);

		$data['success'] = true;
		$data['message'] = "Active";

		return Response::json($data,200,array());
	}

	public function saveCollection($emi_collection_ids){
		foreach ($emi_collection_ids as $key => $emi_collection_id) {
			DB::table('emi_collection')->where("client_id", Auth::user()->client_id)->where('id',$emi_collection_id)->update(['collected_amount'=>1]);
		}

		$data['success'] = true;


		return ;

	}

}



