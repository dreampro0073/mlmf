<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Customer, App\Models\User;

class BlockController extends Controller {

	public function blocks(Request $request){
		$blocks = DB::table('blocks')->select('blocks.*','cities.city_name')->leftJoin('cities','cities.id','=','blocks.city_id')->get();
		return view('admin.blocks.index', [
            "sidebar" => "block",
            "subsidebar" => "block",
            "blocks" => $blocks,
        ]);
	}
	public function addBlock($block_id = 0){
		return view('admin.blocks.add', [
            "sidebar" => "blocks",
            "subsidebar" => "blocks",
            'block_id' => $block_id,
        ]);
	}	

	public function storeBlock(Request $request){
		$cre = [
			'state_id'=>$request->state_id,
			'city_id'=>$request->city_id,
			'block_name'=>$request->block_name,
			
		];

		$rules = [
			'state_id'=>'required',
			'city_id'=>'required',
			'block_name'=>'required',
			
		];

		
		$validator = Validator::make($cre,$rules);

		if($validator->passes()){

			$ins_data = [
				'state_id'=>$request->state_id,
				'city_id'=>$request->city_id,
				'block_name'=>$request->block_name,
			];

			

			if($request->id){
				DB::table('blocks')->where('id',$request->id)->update($ins_data);
				$message = "Successfully Updated";
			}else{
				DB::table('blocks')->insert($ins_data);
				$message = "Successfully Added";

			}

			$data['message'] = $message;
			$data['success'] = true;
			$data['redirect_url'] = url('admin/blocks');
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

	public function blockInit(Request $request){
		$block = DB::table('blocks')->where("id", $request->block_id)->first();
		$data['success'] = true;
		$data['block'] = $block;
		return Response::json($data, 200, []);
	}	
}
