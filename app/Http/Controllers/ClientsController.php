<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Customer, App\Models\User;

class ClientsController extends Controller {

	public function index(Request $request){

		$customers = DB::table("customers")->select('customers.*')->where('status', 1)->where("client_id", Auth::user()->client_id)->get();

		foreach ($customers as $item) {
			if($item->processing_status == 1){
				$item->kyc_status = 'Verified';
			}
			if($item->processing_status == 2){
				$item->kyc_status = 'Processing';
			}
			if($item->processing_status == 3){
				$item->kyc_status = 'Pending';
			}
			if($item->processing_status == 4){
				$item->kyc_status = 'Failed';
			}
		}

		return view('admin.clients.index', [
            "sidebar" => "clients",
            "subsidebar" => "clients",
            "clients" => $customers,
        ]);
	}

	public function addClient($enc_id = 0){
		if($enc_id !== 0 && $enc_id !== ''){
			$customer_id = Customer::getCustomerId($enc_id);
		} else {
			$customer_id = $enc_id;
		}
		return view('admin.clients.add', [
            "sidebar" => "clints",
            "subsidebar" => "clints",
            'client_id' => $customer_id,
        ]);
	}
	
	public function clientDetails($enc_id = 0){
		if($enc_id !== 0 && $enc_id !== ''){
			$customer_id = Customer::getCustomerId($enc_id);
		} else {
			$customer_id = $enc_id;
		}
		return view('admin.clients.client_details', [
            "sidebar" => "clients",
            "subsidebar" => "clients",
            'client_id' => $customer_id,
        ]);
	}
	
	public function historyDetails($enc_id = 0){
		if($enc_id !== 0 && $enc_id !== ''){
			$customer_id = Customer::getCustomerId($enc_id);
		} else {
			$customer_id = $enc_id;
		}

		$customer = DB::table('customers')->select('customers.name','customers.id', 'customer_documents.customer_photo')
		->leftjoin('customer_documents', 'customer_documents.customer_id', '=', 'customers.id')
		->where('customers.client_id', Auth::user()->client_id)
		->where('customers.id', $customer_id)
		->first();

		$groups = DB::table('group_customers')->select('group_customers.group_id','group_customers.id','groups.group_name','group_customers.closed')->leftJoin('groups','groups.id','=','group_customers.group_id')->where("group_customers.client_id", Auth::user()->client_id)->where('group_customers.customer_id', $customer_id)->get();

		foreach ($groups as $group) {
			$group_emi_dates = DB::table('group_emi_dates')->select('group_emi_dates.emi_date','group_emi_dates.emi_amount','emi_collection.collected_amount')->leftjoin('emi_collection', 'emi_collection.group_emi_date_id', 'group_emi_dates.id')->where('emi_collection.customer_id', $customer_id)->where('group_emi_dates.emi_date','<',date("Y-m-d"))->where('group_emi_dates.group_id', $group->group_id)->get();

			foreach ($group_emi_dates as $key => $item) {
				$item->emi_status = 'Not Paid';

				if($item->collected_amount == 1){
					$item->emi_status = 'Paid';

				}
			}

			$group->group_emi_dates = $group_emi_dates;
		}

		// dd($groups);

		return view('admin.clients.history_details', [
            "sidebar" => "clients",
            "subsidebar" => "clients",
            'client_id' => $customer_id,
            'client' => $customer,
            'groups' => $groups,
            'enc_id'=>$enc_id
        ]);
	}


	public function clientInit(Request $request){

		$customer = DB::table('customers')->select('customers.*', 'customer_documents.aadhaar_card', 'customer_documents.pan_card', 'customer_documents.voter_id_card', 'customer_documents.customer_photo', 'customer_documents.bank_passbook','customer_documents.joint_photo', 'banks.bank_name')->leftjoin('customer_documents', 'customer_documents.customer_id', '=', 'customers.id')->leftjoin('banks', 'banks.id', '=', 'customers.bank_id')->where("customers.client_id", Auth::user()->client_id)->where('customers.id', $request->client_id)->first();

		if($customer){
			$customer->aadhaar_no = intval($customer->aadhaar_no);
			$customer->pin = intval($customer->pin);
			$customer->ac_no = intval($customer->ac_no);
			$customer->mobile = intval($customer->mobile);


			$guarantor = DB::table('customer_guarantor')->select('customer_guarantor.*', 'banks.bank_name')->leftjoin('banks', 'banks.id', '=', 'customer_guarantor.bank_id')->where('customer_guarantor.customer_id', $customer->id)->first();

			if($guarantor){
				$guarantor->aadhaar_no = intval($guarantor->aadhaar_no);
				
				$guarantor->ac_no = intval($guarantor->ac_no);
				$guarantor->mobile = intval($guarantor->mobile);
			}
		}	else{
			$guarantor = null;
		}	


		$states = DB::table('states')->select('id', 'state_name')->where('active',1)->get();
		$banks = User::getBanksList();

		$data['success'] = true;
		$data['client'] = $customer;
		$data['states'] = $states;
		$data['guarantor'] = $guarantor;
		$data['banks'] = $banks;

		return Response::json($data, 200, []);
	}		

	public function clientDetailsInit(Request $request){

		$customer = DB::table('customers')->select('customers.*', 'customer_documents.aadhaar_card', 'customer_documents.pan_card', 'customer_documents.voter_id_card', 'customer_documents.customer_photo','customer_documents.bank_passbook','customer_documents.joint_photo', 'states.state_name', 'cities.city_name as district_name', 'blocks.block_name', 'villages.village_name','banks.bank_name')
		->leftjoin('customer_documents', 'customer_documents.customer_id', '=', 'customers.id')
		->leftjoin('states', 'states.id', '=', 'customers.state_id')
		->leftjoin('cities', 'cities.id', '=', 'customers.district_id')
		->leftjoin('blocks', 'blocks.id', '=', 'customers.block_id')
		->leftjoin('villages', 'villages.id', '=', 'customers.village_id')
		
		->leftjoin('banks', 'banks.id', '=', 'customers.bank_id')
		
		->where('customers.id', $request->client_id)
		->where('customers.client_id', Auth::user()->client_id)
		->first();

		$guarantorData = DB::table('customer_guarantor')->select("customer_guarantor.*", 'banks.bank_name')->leftjoin('banks', 'banks.id', '=', 'customer_guarantor.bank_id')->where('customer_id', $customer->id)->first();
		$customer->dob = $customer->dob ? date('d-m-Y', strtotime($customer->dob)) : '';
		
		$data['success'] = true;
		$data['client'] = $customer;
		$data['guarantorData'] = $guarantorData;

		return Response::json($data, 200, []);
	}	

	public function clientStore(Request $req){

		$request = $req->formData;
		$guarantorData = $req->guarantorData;


		$cre = [
			'name'=>$request['name'],
			'mobile'=>$request['mobile'],
			// 'aadhaar_no'=>$request['aadhaar_no'],
		];

		$rules = [
			'name'=>'required',
			'mobile'=>'required',
			// 'aadhaar_no'=>'required|unique:customers',
		];

		if(isset($request['id'])){

		}else{

			$cre['aadhaar_no'] = $request['aadhaar_no'];
			$rules['aadhaar_no'] = 'required|unique:customers';
		}

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){

			$data= [
				'name'=>$request['name'],
				'father_husband_name'=>$request['father_husband_name'],
				'dob'=>$request['dob'] ? date('Y-m-d', strtotime($request['dob'])): '',
				'email'=>isset($request['email'])?$request['email']:null,
				'mobile'=>$request['mobile'],
				'address'=>$request['address'],
				'aadhaar_no'=>$request['aadhaar_no'],
				'voter_id_no'=>isset($request['voter_id_no'])?$request['voter_id_no']:null,
				'pan_no'=>isset($request['pan_no'])?$request['pan_no']:null,
				'pin'=>$request['pin'],
				'status'=>1,
				'state_id'=>$request['state_id'],
				'district_id'=>$request['district_id'],
				'tehsil_id'=>isset($request['tehsil_id'])?$request['tehsil_id']:0,
				'block_id'=>isset($request['block_id'])?$request['block_id']:0,
				'village_id'=>isset($request['village_id'])?$request['village_id']:0,
				'bank_id'=>isset($request['bank_id'])?$request['bank_id']:0,
				'ifsc_code'=>$request['ifsc_code'],
				'ac_no'=>$request['ac_no'],
				'client_id'=>Auth::user()->client_id,
				
			];

			if(isset($request['id'])){
				$customer_id = $request['id'];
				$data['processing_status'] = $request['processing_status'];
				DB::table('customers')->where('id', $request['id'])->update($data);
				$message = "Updated Successfully!";
			} else {
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['processing_status'] = 2;
				$customer_id = DB::table('customers')->insertGetId($data);

				DB::table('customers')->where('id',$customer_id)->update([
                	'enc_id' => md5($customer_id),
            	]);

				$message = "Stored Successfully!";

            	$customer = DB::table('customers')->where('customers.client_id', Auth::user()->client_id)->where('id',$customer_id)->first();

            	if($customer){
            		$year = date("Y",strtotime($customer->created_at));
		            $customer->id;
		            $unique = $year.'000000';
		            $unique_id = $unique + $customer_id;

		            DB::table('customers')->where('customers.client_id', Auth::user()->client_id)->where('id',$customer->id)->update([
		                'unique_id' =>$unique_id,
		            ]);
            	}

			}

            	// if($customer){
            	// 	$year = date("Y",strtotime($customer->created_at));
		           //  $id = $customer->id;
		           //  $unique = $id.$year;
		           //  $un_zero = "";
		           //  $len = strlen($unique);
		            
		           //  switch ($len) {
		           //      case 5:
		           //          $un_zero = "00000";
		           //          break;
		                
		           //      case 6:
		           //          $un_zero = "0000";
		           //          break;
		           //      case 7:
		           //          $un_zero = "000";
		           //          break;
		           //      case 8:
		           //          $un_zero = "00";
		           //          break;
		           //      case 9:
		           //          $un_zero = "0";
		           //          break;
		           //      default:
		           //      break;
		           //  }

		           //  $unique_id = $un_zero.$unique;

		           //  DB::table('customers')->where('id',$customer->id)->update([
		           //      'unique_id' =>$unique_id,
		           //  ]);
            	// }	


			$check = DB::table('customer_documents')->where('customer_id', $customer_id)->first();

			$i_data = [
				'aadhaar_card' => (isset($request['aadhaar_card']))?$request['aadhaar_card']:null,
				'pan_card' => isset($request['pan_card'])?$request['pan_card']:null,
				'voter_id_card' => (isset($request['voter_id_card']))?$request['voter_id_card']:null,
				'customer_photo' => isset($request['customer_photo'])?$request['customer_photo']:null,
				'bank_passbook' => isset($request['bank_passbook'])?$request['bank_passbook']:null,
				'joint_photo' => isset($request['joint_photo'])?$request['joint_photo']:null,
			];
			if($check){
				DB::table('customer_documents')->where('id', $check->id)->update($i_data);
			} else {
				$i_data['created_at'] = date('Y-m-d H:i:s');
				$i_data['customer_id'] = $customer_id;
				DB::table('customer_documents')->insert($i_data);
			}			

			$ins_data = [
				'customer_id' => $customer_id,
				'aadhaar_card' => (isset($guarantorData['aadhaar_card']))?$guarantorData['aadhaar_card']:null,
				'pan_card' => (isset($guarantorData['pan_card']))?$guarantorData['pan_card']:null,
				'voter_id_card' => (isset($guarantorData['voter_id_card']))?$guarantorData['voter_id_card']:null,
				'photo' => (isset($guarantorData['photo']))?$guarantorData['photo']:null,
				'name' => (isset($guarantorData['name']))?$guarantorData['name']:null,
				'email' => (isset($guarantorData['email']))?$guarantorData['email']:null,
				'mobile' => (isset($guarantorData['mobile']))?$guarantorData['mobile']:null,
				'bank_id' => (isset($guarantorData['bank_id']))?$guarantorData['bank_id']:null,
				'ifsc_code' => (isset($guarantorData['ifsc_code']))?$guarantorData['ifsc_code']:null,
				'ac_no' => (isset($guarantorData['ac_no']))?$guarantorData['ac_no']:null,
				'aadhaar_no' => (isset($guarantorData['aadhaar_no']))?$guarantorData['aadhaar_no']:null,
				'voter_id_no' => (isset($guarantorData['voter_id_no']))?$guarantorData['voter_id_no']:null,
				'pan_no' => (isset($guarantorData['pan_no']))?$guarantorData['pan_no']:null,
				// 'created_at' => date('Y-m-d H:i:s'),
			];

			// dd($ins_data);

			if(isset($guarantorData['id'])){

				DB::table('customer_guarantor')->where('customer_id',$customer_id)->where('id',$guarantorData['id'])->update($ins_data);
			}else{
			

				$ins_data['created_at'] =  date('Y-m-d H:i:s');
				DB::table('customer_guarantor')->insert($ins_data);
			}
			

			$data['message'] = $message;
			$data['success'] = true;
			$data['redirect_url'] = url('admin/clients');
		}else{
			$error = "";
			$messages = $validator->messages();

			foreach ($messages->all() as $message) {
				$error = $message;
				break;
			}
			$data['message'] = $error;
			$data['success'] = false;
		}


		return Response::json($data, 200, []);
	}
	
	public function getDistricts(Request $request){
		$districts = DB::table('cities')->where('state_id', $request->state_id)->where('active', 1)->get();
		$data['districts'] = $districts;
		$data['success'] = true;

		return Response::json($data, 200, []);
	}

	
	public function getBlocks(Request $request){
		$blocks = DB::table('blocks')->where('city_id', $request->district_id)->get();
		$data['blocks'] = $blocks;
		$data['success'] = true;

		return Response::json($data, 200, []);
	}

	
	public function geVtillages(Request $request){
		$villages = DB::table('villages')->where('block_id', $request->block_id)->get();
		$data['villages'] = $villages;
		$data['success'] = true;

		return Response::json($data, 200, []);
	}	
	public function deleteClient($customer_id){
		$check = DB::table('group_customers')->where('group_customers.client_id', Auth::user()->client_id)->where('customer_id', $customer_id)->where('status', 1)->count();
		if($check > 0){
			return Redirect::back()->with('success', "You can not delete this customer, Because this customer active in a group !");
		} else {
			DB::table('customers')->where('customers.client_id', Auth::user()->client_id)->where('enc_id', $customer_id)->update([
				'status' => 0,
			]);
			return Redirect::back()->with('success', "Delete Successfully");
		}
	}
}
