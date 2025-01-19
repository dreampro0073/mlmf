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

class GroupsControllerOld extends Controller {

	public function index(Request $request){
		$groups = DB::table("groups")->select('groups.*','plans.plan_name','blocks.block_name','villages.village_name')->leftjoin('plans','plans.id','=','groups.plan_id')->leftjoin('blocks','blocks.id','=','groups.block_id')->leftjoin('villages','villages.id','=','groups.village_id')->where('groups.status', 1)->orderBy('groups.id', 'DESC')->get();

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
		$customers = DB::table('customers')->select('id','name')->get();
		$group = DB::table('groups')->where('id', $request->group_id)->first();

		if($group){
			$group_customers = DB::table('group_customers')->select('customers.id','customers.name')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group->id)->get();

			$group->customers = $group_customers;
			if($group->start_date){
				$group->start_date = date("m/d/Y",strtotime($group->start_date));
			} 
		}

		$blocks = DB::table('blocks')->get();
		$plans = DB::table("plans")->select('id','plan_name')->where('status', 1)->get();

		$data['success'] = true;
		$data['group'] = $group;
		$data['plans'] = $plans;
		$data['blocks'] = $blocks;
		$data['customers'] = $customers;


		return Response::json($data, 200, []);
	}		


	public function groupViewInit(Request $request){

		$group = DB::table('groups')->select('groups.*','villages.village_name', 'plans.emi_amount','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftjoin('villages','villages.id','=','groups.village_id')->leftjoin('plans','plans.id','=','groups.plan_id')->where('groups.id','=',$request->group_id)->first();

		
		$group_id = $request->group_id;
		

		if($group){
			$group_customers = DB::table('group_customers')->select('customers.*','customers.name','group_customers.id as group_customer_id','group_customers.purpose')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group->id)->get();

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


		$group_dates = DB::table('group_emi_dates')->select('group_emi_dates.id as group_emi_date_id','group_emi_dates.group_id','group_emi_dates.emi_date','groups.group_name','villages.village_name','group_emi_dates.emi_amount')->leftjoin('groups','groups.id','=','group_emi_dates.group_id')->leftjoin('villages','villages.id','=','groups.village_id')->where('group_emi_dates.emi_date','=',date("Y-m-d"))->get();



		$total_amount = 0;
		foreach ($group_dates as $key => $group_date) {
			$group_customers = DB::table('group_customers')->select('customers.name','customers.aadhaar_no')->leftjoin('customers','customers.id','=','group_customers.customer_id')->where('group_customers.group_id','=',$group_date->group_id)->get();

			$group_date->group_customers = $group_customers;

			$total_amount = sizeof($group_customers)*$group_date->emi_amount;
		}

		$data['success'] = true;
		$data['group_dates'] = $group_dates;
		$data['total_amount'] = $total_amount;
		

		return Response::json($data, 200, []);
	}

	public function viewCollection(Request $request){

		$group = DB::table('groups')->select('groups.*','villages.village_name')->leftjoin('villages','villages.id','=','groups.village_id')->where('groups.id', $request->group_id)->first();
		$group_id = $request->group_id;

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();

			$group_customers  = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where('group_customers.group_id',$group->id)->get();

			$group->group_dates = $group_dates;
			// $group->group_customers = $group_customers;

			$show_customers = [];

			if(sizeof($group_customers) > 0){
				foreach ($group_customers as $key => $group_customer) {
					if(sizeof($group_dates) > 0){
						foreach ($group_dates as $key => $group_date) {

							$check_entry = DB::table('emi_collection')->where('group_id',$group_id)->where("customer_id",$group_customer->customer_id)->where('group_emi_date_id',$group_date->id)->first();

							$entry_data = [
								'group_id' => $group_id,
								'customer_id' => $group_customer->customer_id,
								'group_emi_date_id' => $group_date->id,
							];

							if(!$check_entry){
								DB::table("emi_collection")->insert($entry_data);
							}
						}
					}
				}
			}


			foreach ($group_dates as $group_date) {
				$g_customers = DB::table('group_customers')->select('customers.name','customers.aadhaar_no','group_customers.customer_id')->leftjoin('customers','group_customers.customer_id','=','customers.id')->where('group_customers.group_id',$group->id)->get(); 



				foreach ($g_customers as $key => $g_customer) {
					$check_entry = DB::table('emi_collection')->where('group_id',$group_id)->where("customer_id",$g_customer->customer_id)->where('group_emi_date_id',$group_date->id)->first();

					$emi_collected = false;
					$future_emi = false;
					$is_enabled = false;
					$is_checked = false;

					if($check_entry->collected_amount){
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
				
			];



			if($request->id){
				$group_id = $request->id;
				$data['start_date'] = date("Y-m-d",strtotime($request->start_date));
				
				DB::table('groups')->where('id', $request->id)->update($data);
				$message = "Updated Successfully!";
			} else {
				$data['start_date'] = date("Y-m-d",strtotime($request->start_date));
				$data['created_at'] = date('Y-m-d H:i:s');
				$group_id = DB::table('groups')->insertGetId($data);
				$message = "Stored Successfully!";
			}

			$customers = $request->customers;

			$group_customer_ids = [];
			if(sizeof($customers) > 0){
				foreach ($customers as $key => $customer) {
					$check = DB::table('group_customers')->where('group_id',$group_id)->where('customer_id',$customer['id'])->first();

					$data = [
						'group_id' =>$group_id,
						'customer_id' => $customer['id'],
					];

					if(!$check){
						$group_customer_id = DB::table('group_customers')->insertGetId($data);
					}else{
						DB::table('group_customers')->where('group_id',$group_id)->where('customer_id',$customer['id'])->update($data);
						$group_customer_id = $check->id;
					}

					$group_customer_ids[] = $group_customer_id;
				}

				DB::table('group_customers')->where('group_id',$group_id)->whereNotIn('id',$group_customer_ids)->delete();
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
		$group_id = $request->group_id;
		$plan = DB::table('groups')->select('plans.*','groups.start_date','groups.second_date')->leftjoin('plans','plans.id','=','groups.plan_id')->where('groups.id',$group_id)->first();

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
	       	}else{

	       		$start_date = date("Y-m-d",strtotime($plan->start_date));
	        	$second_date = date("Y-m-d",strtotime("+15 days".$start_date));

		        $f1_day =  date("d",strtotime($start_date));
		        $f2_day =  date("d",strtotime($second_date));

		        $f1_mon =  date("m",strtotime($start_date));
		        $f2_mon =  date("m",strtotime($second_date));

		        $y1_year = date("Y",strtotime($start_date));
		        $y2_year = date("Y",strtotime($second_date));

		        $s_date1 = $start_date;
		        $s_date2 = $second_date;

	       		for ($i=0; $i < $plan->no_of_emis/2; $i++) { 

		       		// $s_date1 = date($y1_year."-".$f1_mon.'-'.$f1_day,strtotime($s_date1));
		         //    $s_date2 = date($y2_year."-".$f2_mon.'-'.$f2_day,strtotime($s_date2));

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

		            $dates[] = $s_date1;
		            $dates[] = $s_date2;
		        }	       		

		        // for ($i=0; $i < $plan->no_of_emis/2; $i++) { 

		       	// 	$s_date1 = date($y1_year."-".$f1_mon.'-'.$f1_day,strtotime($s_date1));
		        //     $s_date2 = date($y2_year."-".$f2_mon.'-'.$f2_day,strtotime($s_date2));

		        //     if($f1_mon == 12){
		        //         $f1_mon = "01";
		        //         $y1_year++;
		        //     }else{
		        //         $f1_mon= date("m",strtotime("+1 month".$s_date1));

		        //     }   
		        //     if($f2_mon == 12){
		        //         $f2_mon = "01";
		        //         $y2_year++;

		        //     }else{
		        //         $f2_mon= date("m",strtotime("+1 month".$s_date2));
		        //     }

		        //     $dates[] = $s_date1;
		        //     $dates[] = $s_date2;
		        // }
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

		DB::table('groups')->where('id',$group_id)->update([
			'active' => 1
		]);

		$data['success'] = true;
		$data['message'] = "Active";

		return Response::json($data,200,array());
	}
	
	public function deleteGroup($group_id){
		$check = DB::table('group_customers')->where('group_id', $group_id)->get();
		if(sizeof($check)> 0){
			DB::table('groups')->where('id', $group_id)->update([
				'status' => 0,
			]);

		} else {
			DB::table('groups')->where('id', $group_id)->delete();
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
		$group = DB::table('groups')->select('groups.*','villages.village_name')->leftjoin('villages','villages.id','=','groups.village_id')->where('groups.id', $request->group_id)->first();
		
		$sql = DB::table('group_customers')
		->select('group_customers.*','customers.name','group_emi_dates.emi_date')
		->leftjoin('customers','customers.id', '=', 'group_customers.customer_id')
		->leftjoin('group_emi_dates', 'group_emi_dates.group_id', '=', 'group_customers.id')
		->where('group_customers.group_id', $request->group_id)
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
		$customers = DB::table("customers")->select("id as value","aadhaar_no as label","name")->where('customers.aadhaar_no','LIKE','%'.$request->term.'%')->get();


		foreach ($customers as $key => $customer) {
			$customer->label = $customer->label.'/'.$customer->name;
		}

 		return Response::json($customers,200,[]);
	}
	
	public function payEMI(Request $request){
		$pay_collection = DB::table("emi_collection")->where('id', $request->emi_collection_id)->first();
		$data['pay_collection'] = $pay_collection;

 		return Response::json($data,200,[]);
	}
	
	public function paidEMI(Request $request){
		DB::table("emi_collection")->where('id', $request->emi_collection_id)->update([
			'remark'=>$request->remark,
			'penalty'=>$request->penalty,
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


		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $request->group_id)->first();
		$group_id = $request->group_id;

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


		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $request->group_id)->first();

		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.email','customers.mobile','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_documents.customer_photo','customers.unique_id')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->where('customers.id','=',$customer_id)->first();

		// dd($customer);

		$group_id = $request->group_id;

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->where('collected_amount',1)->first();

                // dd($check);
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

		$data['success'] = true;
		$data['message'] = 'Successfully Updated!';
		$data['group_id'] = $group_id;
		$data['group'] = $group;
		$data['customer'] = $customer;

 		return Response::json($data,200,[]);

	}

	public function printLoanCard($group_id,$customer_id){

		$options = new Options();
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);


		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis','plans.time_line')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->where('groups.id', $group_id)->first();

		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.email','customers.mobile','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_guarantor.photo as guarantor_photo','customer_documents.customer_photo','group_customers.id as group_customer_id','customers.unique_id')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->leftJoin('group_customers','group_customers.customer_id','=','customers.id')->where('customers.id','=',$customer_id)->where('group_customers.group_id',$group_id)->first();

		$total_amount =0;
		$total_int_amount =0;
		

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->where('collected_amount',1)->first();

                $total_amount += $group_date->emi_amount;
                $total_int_amount += $group_date->interest_payment;

                // dd($check);
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

		// return view('admin.groups.c_loan_card_print',compact('group','customer','total_amount','total_int_amount'));
		$html = view('admin.groups.c_loan_card_print',compact('group','customer','total_amount','total_int_amount'));

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4',);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream();


	}

	public function shapatPatra($group_id,$customer_id){

		$options = new Options();
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);


		$group = DB::table('groups')->select('groups.*','villages.village_name','plans.principal_amount','plans.interest_rate','plans.no_of_emis','plans.time_line','blocks.block_name','emi_types.type_name')->leftJoin('villages','villages.id','=','groups.village_id')->leftJoin('plans','plans.id','=','groups.plan_id')->leftJoin('emi_types','emi_types.id','=','plans.emi_type')->leftJoin('blocks','blocks.id','=','groups.block_id')->where('groups.id', $group_id)->first();

		
		$customer = DB::table('customers')->select('customers.name','customers.father_husband_name','customers.email','customers.mobile','customers.aadhaar_no','customers.pan_no','customers.voter_id_no','customers.dob','customer_guarantor.name as guarantor_name','customer_guarantor.mobile as guarantor_mobile','customer_guarantor.photo as guarantor_photo','customer_guarantor.aadhaar_no as guarantor_aadhaar_no','customer_guarantor.pan_no as guarantor_pan_no','customer_documents.customer_photo','group_customers.id as group_customer_id','b1.bank_name as c_bank_name','customers.ac_no','customers.ifsc_code','b2.bank_name as guarantor_bank_name','customer_guarantor.ifsc_code as guarantor_ifsc_code','customer_guarantor.ac_no as guarantor_ac_no','customer_guarantor.voter_id_no as guarantor_voter_id_no','group_customers.purpose')->leftJoin('customer_documents','customer_documents.customer_id','=','customers.id')->leftJoin('customer_guarantor','customer_guarantor.customer_id','=','customers.id')->leftJoin('group_customers','group_customers.customer_id','=','customers.id')->leftJoin('banks as b1','b1.id','=','customers.bank_id')->leftJoin('banks as b2','b2.id','=','customer_guarantor.bank_id')->where('customers.id','=',$customer_id)->where('group_customers.group_id',$group_id)->first();
		$total_amount =0;
		$total_int_amount =0;
		

		if($group){
			$group_dates = DB::table('group_emi_dates')->where('group_id',$group->id)->get();


			foreach ($group_dates as $group_date) {
                $check = DB::table('emi_collection')->where('group_id',$group_id)->where('customer_id',$customer_id)->where('group_emi_date_id',$group_date->id)->where('collected_amount',1)->first();

                $total_amount += $group_date->emi_amount;
                $total_int_amount += $group_date->interest_payment;

                // dd($check);
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
		$dompdf->stream();


	}

	public function getPlanType(Request $request){
		$plan = Plan::find($request->plan_id);
		$data['plan'] = $plan;
		$data['success'] = true;
		return Response::json($data, 200, []);
	}

	public function storePurpose(Request $request, $group_customer_id){
		$purpose = $request->purpose;

		DB::table('group_customers')->where('id',$group_customer_id)->update([
			'purpose' => $purpose,
		]);
		
		$data['success'] = true;
		return Response::json($data, 200, []);
	}
}


