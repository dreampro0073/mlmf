<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Customer, App\Models\User;

class VillageController extends Controller {

	public function index(Request $request){
		$villages = DB::table('villages')->select('villages.*','cities.city_name','blocks.block_name')->leftJoin('cities','cities.id','=','villages.city_id')->leftJoin('blocks','blocks.id','=','villages.block_id')->orderBy('villages.id','DESC')->get();
		return view('admin.villages.index', [
            "sidebar" => "villages",
            "subsidebar" => "villages",
            "villages" => $villages,
        ]);
	}
	public function add($village_id = 0){
		return view('admin.villages.add', [
            "sidebar" => "villages",
            "subsidebar" => "villages",
            'village_id' => $village_id,
        ]);
	}	

	public function store(Request $request){
		$cre = [
			'state_id'=>$request->state_id,
			'city_id'=>$request->city_id,
			'block_id'=>$request->block_id,
			'village_name'=>$request->village_name,
			
		];

		$rules = [
			'state_id'=>'required',
			'city_id'=>'required',
			'block_id'=>'required',
			'village_name'=>'required',
			
		];
		$validator = Validator::make($cre,$rules);
		if($validator->passes()){

			$ins_data = [
				'state_id'=>$request->state_id,
				'city_id'=>$request->city_id,
				'block_id'=>$request->block_id,
				'village_name'=>$request->village_name,
			];

			

			if($request->id){
				DB::table('villages')->where('id',$request->id)->update($ins_data);
				$message = "Successfully Updated";
			}else{
				DB::table('villages')->insert($ins_data);
				$message = "Successfully Added";

			}

			$data['message'] = $message;
			$data['success'] = true;
			$data['redirect_url'] = url('admin/villages');
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

	public function init(Request $request){
		$block = DB::table('villages')->where("id", $request->village_id)->first();
		$data['success'] = true;
		$data['block'] = $block;
		return Response::json($data, 200, []);
	}	
}
