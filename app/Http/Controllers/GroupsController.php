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

class GroupsController extends Controller {

	public function index(Request $request){
		$groups = DB::table("groups")->select('groups.*','plans.plan_name','blocks.block_name','villages.village_name')->leftjoin('plans','plans.id','=','groups.plan_id')->leftjoin('blocks','blocks.id','=','groups.block_id')->leftjoin('villages','villages.id','=','groups.village_id')->where("groups.client_id", Auth::user()->client_id)->where('groups.status', 1)->orderBy('groups.id', 'DESC')->get();

		return view('admin.groups.index', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            "groups" => $groups,
        ]);
	}

	public function addGroup($group_id = 0){
		return view('admin.groups.add', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            'group_id' => $group_id,
        ]);
	}

	public function viewGroup($group_id = 0){
		return view('admin.groups.view', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            'group_id' => $group_id,
        ]);
	}
	
	public function todayCollection($group_id = 0){
		return view('admin.groups.collection_status', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            'group_id' => $group_id,
        ]);
	}


	public function groupInit(Request $request){
		$client_id = Auth::user()->client_id;
		$customers = DB::table('customers')->where('status', 1)->where('processing_status', 1)->select('id','name')->where("customers.client_id", $client_id)->get();
		$group = DB::table('groups')->where("groups.client_id", $client_id)->where('id', $request->group_id)->first();

		if($group){
			
			$group_customers = DB::table('group_customers')->select('customers.id','customers.name')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group->id)->get();

			$group->customers = $group_customers;
			if($group->start_date){
				$group->start_date = date("m/d/Y",strtotime($group->start_date));
				$group->day = (date('d', strtotime($group->start_date)))*1;
			} 
		}

		$blocks = DB::table('blocks')->get();
		$plans = DB::table("plans")->select('id','plan_name')->where("client_id", $client_id)->where('status', 1)->get();

		$data['success'] = true;
		$data['group'] = $group;
		$data['plans'] = $plans;
		$data['blocks'] = $blocks;
		$data['customers'] = $customers;


		return Response::json($data, 200, []);
	}		


	public function groupViewInit(Request $request){
		$client_id = Auth::user()->client_id;
		$group = DB::table('groups')->select('groups.*','villages.village_name', 'plans.emi_amount','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftjoin('villages','villages.id','=','groups.village_id')->leftjoin('plans','plans.id','=','groups.plan_id')->where('groups.id','=',$request->group_id)->where("groups.client_id", $client_id)->first();

		
		$group_id = $request->group_id;
		

		if($group){
			$group_customers = DB::table('group_customers')->select('customers.*','customers.name','group_customers.id as group_customer_id','group_customers.purpose','group_customers.invoice')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group->id)->get();

			$group->customers = $group_customers;


			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();

			$group_customers1  = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where('group_customers.group_id',$group->id)->get();

			if(sizeof($group_customers1) > 0){
				foreach ($group_customers1 as $key => $group_customer) {
					if(sizeof($group_dates) > 0){
						foreach ($group_dates as $key => $group_date) {
							$check_entry = DB::table('emi_collection')->where('group_id',$group->id)->where("customer_id",$group_customer->customer_id)->where('group_emi_date_id',$group_date->id)->first();

							$entry_data = [
								'group_id' => $group->id,
								'customer_id' => $group_customer->customer_id,
								'group_emi_date_id' => $group_date->id,
								'client_id'=>$client_id,
							];

							if(!$check_entry){
								DB::table("emi_collection")->insert($entry_data);
							}
						}
					}
				}
			}
		}

		$data['success'] = true;
		$data['group'] = $group;
		

		return Response::json($data, 200, []);
	}

	public function todayCollectionInit(Request $request){
		$client_id = Auth::user()->client_id;

		$group_dates = DB::table('group_emi_dates')->select('group_emi_dates.id as group_emi_date_id','group_emi_dates.group_id','group_emi_dates.emi_date','groups.group_name','villages.village_name','group_emi_dates.emi_amount')->join('groups','groups.id','=','group_emi_dates.group_id')->leftjoin('villages','villages.id','=','groups.village_id')->where('group_emi_dates.emi_date','=',date("Y-m-d"))->where("groups.client_id", $client_id)->get();

		$total_amount = 0;
		foreach ($group_dates as $key => $group_date) {
			$group_customers = DB::table('group_customers')
			->select('customers.name','customers.aadhaar_no','customers.mobile','customers.enc_id','group_customers.customer_id')
			->leftjoin('customers','customers.id','=','group_customers.customer_id')
			->where('group_customers.group_id','=',$group_date->group_id)
			->where("group_customers.closed", 0)
			->get();

			$final_group_customers = [];

			foreach($group_customers as $group_customer){

				$check = DB::table('emi_collection')->where('group_emi_date_id', $group_date->group_emi_date_id)->where('customer_id', $group_customer->customer_id)->where('collected_amount', null)->where("client_id", $client_id)->first();
				if($check){
					$final_group_customers[] = $group_customer;
				}
			}

			$group_date->group_customers = $final_group_customers;

			$total_amount = sizeof($group_customers)*$group_date->emi_amount;
		}

		$data['success'] = true;
		$data['group_dates'] = $group_dates;
		$data['total_amount'] = $total_amount;
		

		return Response::json($data, 200, []);
	}

	public function printTodayCollectionInit(Request $request){
		$client_id = Auth::user()->client_id;

		$group_dates = DB::table('group_emi_dates')->select('group_emi_dates.id as group_emi_date_id','group_emi_dates.group_id','group_emi_dates.emi_date','groups.group_name','villages.village_name','group_emi_dates.emi_amount')->join('groups','groups.id','=','group_emi_dates.group_id')->leftjoin('villages','villages.id','=','groups.village_id')->where('group_emi_dates.emi_date','=',date("Y-m-d"))->where("groups.client_id", $client_id)->get();

		$total_amount = 0;

		foreach ($group_dates as $key => $group_date) {
			$group_customers = DB::table('group_customers')->select('customers.name','customers.father_husband_name','customers.mobile', 'villages.village_name', 'customer_guarantor.mobile as guarantor_mobile','group_customers.customer_id')
			->leftjoin('customers','customers.id','=','group_customers.customer_id')
			->leftjoin('customer_guarantor','customers.id','=','customer_guarantor.customer_id')
			->leftjoin('villages','villages.id','=','customers.village_id')
			->where('group_customers.group_id','=',$group_date->group_id)
			->where('group_customers.closed', 0)
			->get();

			$final_group_customers = [];

			foreach($group_customers as $group_customer){

				$check = DB::table('emi_collection')->where('group_emi_date_id', $group_date->group_emi_date_id)->where('customer_id', $group_customer->customer_id)->where('collected_amount', null)->where("client_id", $client_id)->first();
				if($check){
					$final_group_customers[] = $group_customer;
				}
			}

			$group_date->group_customers = $final_group_customers;

			$total_amount += sizeof($group_customers)*$group_date->emi_amount;
		}

		$options = new Options();
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);

		define("DOMPDF_UNICODE_ENABLED", true);
		
		$html = view('admin.groups.print_today_target',compact('group_dates','total_amount'));

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4',);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('today_target'.date("dmY",strtotime("now")).'.pdf');
		

		return Response::json($data, 200, []);
	}

	public function viewCollection(Request $request){

		$group = DB::table('groups')->select('groups.*','villages.village_name')->leftjoin('villages','villages.id','=','groups.village_id')->where("groups.client_id", Auth::user()->client_id)->where('groups.id', $request->group_id)->first();
		$group_id = $request->group_id;

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();

			foreach ($group_dates as $group_date) {
				$g_customers = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where('group_customers.group_id',$group->id)->where("group_customers.closed",0)->get(); 



				foreach ($g_customers as $key => $g_customer) {
					$check_entry = DB::table('emi_collection')->where('group_id',$group_id)->where("customer_id",$g_customer->customer_id)->where('group_emi_date_id',$group_date->id)->first();

					$emi_collected = false;
					$future_emi = false;
					$is_enabled = false;
					$is_checked = false;

					if($check_entry->collected_amount == 1){
						$emi_collected = true;	
					}

					if($group_date->emi_date > date("Y-m-d")){
						$future_emi = true;
					}

					if(date("Y-m-d",strtotime("now")) == $group_date->emi_date){
						$is_enabled = true;
						

					}
					
					$g_customer->emi_collected = $emi_collected;
					$g_customer->future_emi = $future_emi;
					$g_customer->is_enabled = $is_enabled;
					$g_customer->is_checked = $is_checked;
					$g_customer->emi_date = $group_date->emi_date;
					$g_customer->emi_collection_id = $check_entry->id;
					$g_customer->old_balance = 0;
					$g_customer->old_balance = DB::table('emi_balence')->where('customer_id', $g_customer->customer_id)->where('collection_status',0)->sum('balance_amount');
				}

				$group_date->emi_date = date("d/m/Y",strtotime($group_date->emi_date));


				$group_date->customers = $g_customers;
			}
			$group->group_dates = $group_dates;
		
		}

		$data['success'] = true;
		$data['group'] = $group;
		

		return Response::json($data, 200, []);
	}

	public function saveCollection(Request $request){
		$emi_collection_ids = $request->emi_collection_ids;

		foreach ($emi_collection_ids as $key => $emi_collection_id) {
			DB::table('emi_collection')->where('id',$emi_collection_id)->update(['collected_amount'=>1]);
		}

		$data['success'] = true;


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

		if($validator->passes()){

			$data= [
				'group_name'=>$request->group_name,
				'plan_id'=>$request->plan_id,
				'block_id'=>$request->block_id,
				'village_id'=>$request->village_id,
				'pin_code'=>$request->pin_code,
				'insurance_fee'=>$request->insurance_fee,
				'processing_fee'=>$request->processing_fee,

				'start_date' => date('Y-m-', strtotime('now')).($request->day < 10 ?'0'.$request->day : $request->day),
				
			];

			if($request->id){
				$group_id = $request->id;
				
				DB::table('groups')->where("client_id", $client_id)->where('id', $request->id)->update($data);
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

					$data = [
						'group_id' =>$group_id,
						'customer_id' => $customer['id'],
						'client_id' => $client_id,
					];

					if(!$check){
						$group_customer_id = DB::table('group_customers')->insertGetId($data);
					}else{
						DB::table('group_customers')->where("client_id", $client_id)->where('group_id',$group_id)->where('customer_id',$customer['id'])->update($data);
						$group_customer_id = $check->id;
					}

					$group_customer_ids[] = $group_customer_id;
				}

				DB::table('group_customers')->where("client_id", $client_id)->where('group_id',$group_id)->whereNotIn('id',$group_customer_ids)->delete();
			}

			$data['message'] = $message;
			$data['success'] = true;
			$data['redirect_url'] = url('admin/groups');
		}else{
			$data['message'] = $validator->errors();
			$data['success'] = false;
		}


		return Response::json($data, 200, []);
	}

	public function actvateGroup(Request $request){
		$client_id = Auth::user()->client_id();
		$group_id = $request->group_id;
		$check_group = DB::table("groups")->where("client_id", $client_id)->where("id", $group_id)->first();
		if(!$check_group){
			die("Not Authorised!");
		}

		$plan = DB::table('groups')->select('plans.*','groups.start_date','groups.second_date')->leftjoin('plans','plans.id','=','groups.plan_id')->where("client_id", $client_id)->where('groups.id',$group_id)->first();


		if($plan){
			$dates = [];
				       	
	       	if($plan->emi_type == 5){
	       		$start_date = date("Y-m-d",strtotime("+1 month".$plan->start_date));
	        	$second_date = date("Y-m-d",strtotime("+15 days".$start_date));

		        $f1_day =  date("d",strtotime($start_date));
		        $f2_day =  date("d",strtotime($second_date));

		        $f1_mon =  date("m",strtotime($start_date));
		        $f2_mon =  date("m",strtotime($second_date));

		        $y1_year = date("Y",strtotime($start_date));
		        $y2_year = date("Y",strtotime($second_date));

		        $s_date1 = $start_date;
		        $s_date2 = $second_date;
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
	       		if($start_date < date('Y-m-d')){
	       			$start_date = date("Y-m-d",strtotime("+1 month".$plan->start_date));
	       		}
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

		            if(strtotime($s_date1) - strtotime($active_date) >= '567000' && $i < $plan->no_of_emis ){
		            	$dates[] = $s_date1;

		            	$i++;
		            }		            

		            if(strtotime($s_date2) - strtotime($active_date) >= '567000' && $i < $plan->no_of_emis ){
		            	$dates[] = $s_date2;
		            	$i++;

		            }
		        }	       		       		
	       	}
	       	
	       	if($plan->emi_type == 3){

	       		$s_date = date("Y-m-d",strtotime($plan->start_date));

		        $active_date = date("Y-m-d",strtotime($plan->updated_at));

	       		for ($i=0; $i < $plan->no_of_emis; $i) { 

		            $s_date = date('Y-m-d',strtotime($s_date));

		            if(strtotime($s_date) - strtotime($active_date) >= '567000' && $i < $plan->no_of_emis ){
		            	$dates[] = $s_date;

		            	$i++;
		            }	

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
					DB::table('group_emi_dates')->insert($s_dt);	
				}
			}
		}

		$group_dates = DB::table('group_emi_dates')->where('group_id',$group_id)->get();

		$group_customers  = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where("client_id", $client_id)->where('group_customers.group_id',$group_id)->get();

		$group_dates = $group_dates;
		// $group->group_customers = $group_customers;

		$show_customers = [];

		if(sizeof($group_customers) > 0){
			foreach ($group_customers as $key => $group_customer) {
				if(sizeof($group_dates) > 0){
					foreach ($group_dates as $key => $group_date) {
						$check_entry = DB::table('emi_collection')->where('group_id',$group_id)->where("customer_id",$group_customer->customer_id)->where('group_emi_date_id',$group_date->id)->where("client_id", $client_id)->first();

						$entry_data = [
							'group_id' => $group_id,
							'customer_id' => $group_customer->customer_id,
							'group_emi_date_id' => $group_date->id,
							'client_id'=>$client_id,
						];

						if(!$check_entry){
							DB::table("emi_collection")->insert($entry_data);
						}
					}
				}
			}
		}

		DB::table('groups')->where("client_id", $client_id)->where('id',$group_id)->update([
			'active' => 1
		]);

		$data['success'] = true;
		$data['message'] = "Active";

		return Response::json($data,200,array());
	}
	
	public function deleteGroup($group_id){
		$check = DB::table('group_customers')->where("client_id", Auth::user()->client_id)->where('group_id', $group_id)->get();
		if(sizeof($check)> 0){
			DB::table('groups')->where('id', $group_id)->update([
				'status' => 0,
			]);

		} else {
			DB::table('groups')->where("client_id", $client_id)->where('id', $group_id)->delete();
		}
		return Redirect::back()->with('success', "Delete Successfully");
	}
	
	public function emiStatus($group_id){
		
		return view('admin.groups.emi_status', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            'group_id' => $group_id,
        ]);
		
	}

	public function groupEMIInit(Request $request){
		$today = date("Y-m-d");
		$client_id = Auth::user()->client_id;
		$group = DB::table('groups')->select('groups.*','villages.village_name')->leftjoin('villages','villages.id','=','groups.village_id')->where("groups.client_id", $client_id)->where('groups.id', $request->group_id)->first();
		
		$sql = DB::table('group_customers')
		->select('group_customers.*','customers.name','group_emi_dates.emi_date')
		->leftjoin('customers','customers.id', '=', 'group_customers.customer_id')
		->leftjoin('group_emi_dates', 'group_emi_dates.group_id', '=', 'group_customers.id')
		->where('group_customers.group_id', $request->group_id)
		->where('group_customers.client_id', $client_id)
		->where('group_emi_dates.emi_status', 0)
		->where('group_emi_dates.status', 0)
		->where('group_emi_dates.emi_date', '=', $today);
		$count = $sql->count();

		$customers_emis = $sql->get();

		$data['group'] = $group;
		$data['customers_emis'] = $customers_emis;
		$data['success'] = true;

		return Response::json($data,200,[]);;
	}

	public function addCollection($group_id){
		return view('admin.groups.add_collection', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            "group_id" => $group_id,
        ]);
	}

	public function searchCustomers(Request $request){
		$customers = DB::table("customers")->where("client_id", Auth::user()->client_id)->select("id as value","aadhaar_no as label","name")->where('customers.aadhaar_no','LIKE','%'.$request->term.'%')->get();


		foreach ($customers as $key => $customer) {
			$customer->label = $customer->label.'/'.$customer->name;
		}

 		return Response::json($customers,200,[]);
	}
	
	public function payEMI(Request $request){
		$pay_collection = DB::table("emi_collection")->where("client_id", Auth::user()->client_id)->where('id', $request->emi_collection_id)->first();
		$data['pay_collection'] = $pay_collection;

 		return Response::json($data,200,[]);
	}
	
	public function paidEMI(Request $request){
		DB::table("emi_collection")->where("client_id", Auth::user()->client_id)->where('id', $request->emi_collection_id)->update([
			'remark'=>$request->remark,
			'penalty_amount'=>$request->penalty_amount,
			'collected_amount'=>$request->collected_amount,
		]);


		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';

 		return Response::json($data,200,[]);
	}

	public function loanCard(Request $request,$group_id){
		return view('admin.groups.loan_card', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            "group_id" => $group_id,
        ]);
	}
	public function cLoanCard(Request $request,$group_id,$customer_id){
		return view('admin.groups.c_loan_card', [
            "sidebar" => "groups",
            "subsidebar" => "groups",
            "group_id" => $group_id,
            "customer_id" => $customer_id,
        ]);
	}
	public function getLoanCard(Request $request){
		$group_id = $request->group_id;

		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $request->group_id)->where("groups.client_id", Auth::user()->client_id)->first();

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $group_date->emi_date = date("d/m/Y",strtotime($group_date->emi_date));
			}
			$group->group_dates = $group_dates;
		
		}

		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';
		$data['group_id'] = $group_id;
		$data['group'] = $group;

 		return Response::json($data,200,[]);

	}

	public function getCLoanCard(Request $request){
		$group_id = $request->group_id;
		$customer_id = $request->customer_id;
		$client_id = Auth::user()->client_id;

		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $request->group_id)->where("groups.client_id", $client_id)->first();

		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.email','customers.mobile','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_documents.customer_photo','customer_documents.joint_photo','customers.unique_id')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->where("customers.client_id", $client_id)->where('customers.id','=',$customer_id)->first();

		$total_amount =0;
		$total_int_amount =0;

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group->id)->where("client_id", $client_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->first();

               	$group_date->emi_collected = false;

               	$group_date->ad_cl= "f_paid";
                if($check->collected_amount == 1){

               		$group_date->ad_cl= "paid";
               		$group_date->emi_collected = true;

                }

                if($group_date->emi_date < date("Y-m-d") && !$check){
               		$group_date->ad_cl= "n_paid";
                }

                $group_date->emi_date = date("d/m/Y",strtotime($group_date->emi_date));
                $group_date->interest_payment = round($group_date->interest_payment,0);
                $group_date->emi_amount = round($group_date->emi_amount,0);
                $group_date->penalty_amount = $check->penalty_amount;
                $group_date->remark = $check->remark;

                $total_amount += $group_date->emi_amount;
                $total_int_amount += $group_date->interest_payment;

			}
			$group->group_dates = $group_dates;
			$group->total_amount = $total_amount;
			$group->total_int_amount = round($total_int_amount,0);
		
		}

		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';
		$data['group_id'] = $group_id;
		$data['group'] = $group;
		$data['customer'] = $customer;

 		return Response::json($data,200,[]);

	}

	public function printLoanCard($group_id,$customer_id){

		$options = new Options();
		$client_id = Auth::user()->client_id;
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);

		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis','plans.time_line')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $group_id)->where("groups.client_id", $client_id)->first();

		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.unique_id','customers.email','customers.mobile','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_guarantor.photo as guarantor_photo','customer_documents.customer_photo','group_customers.id as group_customer_id','customer_documents.joint_photo','customers.unique_id')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->leftJoin('group_customers','group_customers.customer_id','=','customers.id')->where('customers.id','=',$customer_id)->where("customers.client_id", $client_id)->where('group_customers.group_id',$group_id)->first();

		$total_amount =0;
		$total_int_amount =0;
		

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();

			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->where('collected_amount',1)->first();

                $group_date->interest_payment = round($group_date->interest_payment,0);
                $group_date->emi_amount = round($group_date->emi_amount,0);

                $total_amount += $group_date->emi_amount;
                $total_int_amount += $group_date->interest_payment;

               	$group_date->emi_collected = false;

               	$group_date->ad_cl= "f_paid";
                if($check){

               		$group_date->ad_cl= "paid";
                }

                if($group_date->emi_date < date("Y-m-d") && !$check){
               		$group_date->ad_cl= "n_paid";
                }

                $group_date->emi_date = date("d/m/Y",strtotime($group_date->emi_date));

			}
			$group->group_dates = $group_dates;
		
		}

		define("DOMPDF_UNICODE_ENABLED", true);

		$total_amount = round($total_amount,0);
		$total_int_amount = round($total_int_amount,0);
		$html = view('admin.groups.c_loan_card_print',compact('group','customer','total_amount','total_int_amount'));

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4',);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream($customer->unique_id."-".$customer->name.'.pdf');


	}

	public function shapatPatra($group_id,$customer_id){

		$options = new Options();
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);

		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis','plans.time_line','blocks.block_name','emi_types.type_name')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->leftJoin('emi_types','emi_types.id','=','plans.emi_type')->leftJoin('blocks','blocks.id','=','groups.block_id')->where('groups.id', $group_id)->where("groups.client_id", Auth::user()->client_id)->first();
		
		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.email','customers.mobile','customers.aadhaar_no','customers.pan_no','customers.voter_id_no','customers.dob','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_guarantor.photo as guarantor_photo','customer_guarantor.aadhaar_no as guarantor_aadhaar_no','customer_guarantor.pan_no as guarantor_pan_no','customer_documents.customer_photo','group_customers.id as group_customer_id','b1.bank_name as c_bank_name','customers.ac_no','customers.ifsc_code','b2.bank_name as guarantor_bank_name','customer_guarantor.ifsc_code as guarantor_ifsc_code','customer_guarantor.ac_no as guarantor_ac_no','customer_guarantor.voter_id_no as guarantor_voter_id_no','group_customers.purpose','customer_documents.joint_photo','customers.unique_id')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->leftJoin('group_customers','group_customers.customer_id','=','customers.id')->leftJoin('banks as b1','b1.id','=','customers.bank_id')->leftJoin('banks as b2','b2.id','=','customer_guarantor.bank_id')->where('customers.id','=',$customer_id)->where('group_customers.group_id',$group_id)->where("customers.client_id", Auth::user()->client_id)->first();
		$total_amount =0;
		$total_int_amount =0;
		

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->where('collected_amount',1)->where("client_id", Auth::user()->client_id)->first();

                $total_amount += $group_date->emi_amount;
                $total_int_amount += $group_date->interest_payment;
               	$group_date->emi_collected = false;

               	$group_date->ad_cl= "f_paid";
                if($check){

               		$group_date->ad_cl= "paid";

                }

                if($group_date->emi_date < date("Y-m-d") && !$check){
               		$group_date->ad_cl= "n_paid";
                }

                $group_date->emi_date = date("d/m/Y",strtotime($group_date->emi_date));

			}
			$group->group_dates = $group_dates;



			$group->emi_amount = $group->group_dates[0]->emi_amount;
		
		}

		define("DOMPDF_UNICODE_ENABLED", true);
		$html = view('admin.groups.c_shapat_patra',compact('group','customer','total_amount','total_int_amount'));

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4',);
		$dompdf->render();

		$dompdf->stream('sapat'.$customer->unique_id."-".$customer->name.'.pdf');


	}

	public function getPlanType(Request $request){
		$data['plan'] = Plan::where("id", $request->plan_id)->where("client_id", Auth::user()->client_id)->first();
		$data['success'] = true;
		return Response::json($data, 200, []);
	}

	public function storePurpose(Request $request, $group_customer_id){
		$purpose = $request->purpose;

		DB::table('group_customers')->where('id',$group_customer_id)->where("client_id", Auth::user()->client_id)->update([
			'purpose' => $purpose,
		]);
		
		$data['success'] = true;
		return Response::json($data, 200, []);
	}

	public function getPenalty(Request $request){
		$penalty_emi = DB::table('emi_collection')->select('emi_collection.*', 'group_emi_dates.emi_amount')->leftJoin('group_emi_dates', 'group_emi_dates.id', 'emi_collection.group_emi_date_id')->where('emi_collection.id', $request->emi_collection_id)->where('collected_amount', null)->where("emi_collection.client_id", Auth::user()->client_id)->first();

		if($penalty_emi){
			$data['success'] = true;
			$data['penalty_emi'] = $penalty_emi;
		} else {
			$data['success'] = false;
			$data['message'] = 'Data Not Found !';
		}

		return Response::json($data, 200, []);
	}

	public function storePenalty(Request $request){
		DB::table("emi_collection")->where("client_id", Auth::user()->client_id)->where('id', $request->id)->update([
			'remark'=>$request->remark,
			'penalty_amount'=>$request->penalty_amount,
			'collected_amount'=>1,
		]);


		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';

 		return Response::json($data,200,[]);
	}

	public function closeGroup($id,$enc_id){
		DB::table('group_customers')->where("client_id", Auth::user()->client_id)->where('id', $id)->update(['closed'=> 1]);
		$group = DB::table('group_customers')->where("client_id", Auth::user()->client_id)->where('id', $id)->first();

		DB::table('emi_collection')->where('group_id', $group->group_id)->where('customer_id', $group->customer_id)->where("client_id", Auth::user()->client_id)->update(['collected_amount'=>1]);
		return Redirect::to('admin/clients/history/'.$enc_id);
	}

	public function advancedCollect(Request $request){
		DB::table("emi_collection")->where("client_id", Auth::user()->client_id)->where('id', $request->emi_collection_id)->update([
			'remark'=>'Advanced Collected',
			'collected_amount'=>1,
		]);

		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';

 		return Response::json($data,200,[]);
	}

	public function EMIPart(Request $request){

		DB::table('emi_collection')->where("client_id", Auth::user()->client_id)->where('id', $request->id)->update([
			'collected_amount' => 1,
			'remark' => $request->remark,
		]);
		DB::table('emi_balence')->insert([
			'emi_collection_id' => $request->id,
			'emi_amount' => $request->emi_amount,
			'collected_amount' => $request->paid_amount,
			'customer_id' => $request->customer_id,
			'balance_amount' => $request->emi_amount - $request->paid_amount,
			'collection_status' => 0,
			'client_id' => Auth::user()->client_id(),
		]);

		$data['success'] = true;
		$data['message'] = "Collected !";

 		return Response::json($data,200,[]);
	}

	public function oldCollect(Request $request){
		DB::table('emi_balence')->where("client_id", Auth::user()->client_id)->where('customer_id', $request->customer_id)->update([
			'collection_status' => 1,
		]);

		$data['success'] = true;
		$data['message'] = "Collected !";

 		return Response::json($data,200,[]);
	}

	public function updateInvoice(Request $request){
		
		DB::table("group_customers")->where("client_id", Auth::user()->client_id)->where('id', $request->group_customer_id)->update([
			"invoice"=> $request->invoice ? $request->invoice : "",
		]);
		$data['success'] = true;

 		return Response::json($data,200,[]);
	}
}


