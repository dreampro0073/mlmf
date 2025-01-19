<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB,DateTime;

use App\Models\User;
use Crypt;

class UserController extends Controller {

    public function index(){
        return view('index');
    }

    public function test(){
        $start_date = "2022-08-13 00:00:00";
        $st_date = "2022-08-28 00:00:00";

        $var = strtotime($st_date) - strtotime($start_date);

        dd($var);


        // $second_date = date("Y-m-d",strtotime("+14 days".$start_date));
        // $f1_day =  date("d",strtotime($start_date));
        // $f2_day =  date("d",strtotime($second_date));

        // $f1_mon =  date("m",strtotime($start_date));
        // $f2_mon =  date("m",strtotime($second_date));

        // $y1_year = date("Y",strtotime($start_date));
        // $y2_year = date("Y",strtotime($second_date));

        // $s_date1 = $start_date;
        // $s_date2 = $second_date;

        // for ($i=0; $i < 12 ; $i++) { 

        //     $s_date1 = date($y1_year."-".$f1_mon.'-'.$f1_day,strtotime($s_date1));
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
        //     echo $s_date1."<br>";
        //     echo $s_date2."<br>";

        // }
    }

    

    // public function test(){
    //     $customers = DB::table('customers')->select('id','created_at')->get();

    //     foreach ($customers as $key => $customer) {
    //         $year = date("Y",strtotime($customer->created_at));
    //         $id = $customer->id;

    //         $unique = $id.$year;
    //         $un_zero = "";
    //         $len = strlen($unique);
            
    //         switch ($len) {
    //             case 5:
    //                 $un_zero = "00000";
    //                 break;
                
    //             case 6:
    //                 $un_zero = "0000";
    //                 break;
    //             case 7:
    //                 $un_zero = "000";
    //                 break;
    //             case 8:
    //                 $un_zero = "00";
    //                 break;
    //             case 9:
    //                 $un_zero = "0";
    //                 break;
    //             default:
    //             break;
    //         }

    //         $unique_id = $un_zero.$unique;

    //         DB::table('customers')->where('id',$customer->id)->update([
    //             'unique_id' =>$unique_id,
    //         ]);
    //     }

    //     return;
    // }

    // public function testx(){
    //     $test = DB::table('customers')->find(1);

    //     dd($test);
    // }

	public function login(){
        
        // $customers = DB::table('customers')->pluck('id')->toArray();

        // foreach ($customers as $key => $customer_id) {
           
        //     DB::table('customers')->where('id',$customer_id)->update([
        //         'enc_id' => md5($customer_id),
        //     ]);
        // }

        // return;

        // return Hash::make("dipanshu@135");
        
        
		return view('login');
	}


	public function postLogin(Request $request){

		$cre = ["email"=>$request->input("email"),"password"=>$request->input("password"),'active'=>1];
		$rules = ["email"=>"required","password"=>"required"];
		$validator = Validator::make($cre,$rules);
		
        if($validator->passes()){

			
            if(Auth::attempt($cre)){
                
                return Redirect::to('/admin/dashboard');

			} else {
                return Redirect::back()->withInput()->with('failure','Invalid username or password');
			}

		} else {
            return Redirect::back()->withErrors($validator)->withInput();
		}

	}

    public function changePassword(){
        return view('update_password');
    }
    
    public function updatePassword(Request $request){
        $cre = ["old_password"=>$request->old_password,"new_password"=>$request->new_password,"confirm_password"=>$request->confirm_password];
        $rules = ["old_password"=>'required',"new_password"=>'required|min:5',"confirm_password"=>'required|same:new_password'];
        $old_password = Hash::make($request->old_password);
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) { 
            if (Hash::check($request->old_password, Auth::user()->password )) {
                $password = Hash::make($request->new_password);
                $user = User::find(Auth::id());
                $user->password = $password;
                $user->password_check = $request->new_password;
                $user->save();
                
                return Redirect::back()->with('success', 'Password changed successfully ');
                
            } else {
                return Redirect::back()->withInput()->with('failure', 'Old password does not match.');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput()->with('failure','Unauthorised Access or Invalid Password');
    }


    public function usersList(){

        $data = DB::table('users')->where("client_id", Auth::user()->client_id)->get();

        return view('admin.users.index', [
            "sidebar" => "users",
            "subsidebar" => "users",
            "data" =>$data,
        ]);
    }

    public function addUser($user_id = 0){
        return view('admin.users.add', [
            "sidebar" => "users",
            "subsidebar" => "users",
    
            "user_id"=>$user_id,
        ]);
    }

    public function initUser(Request $request){


        $user = DB::table('users')->where("client_id", Auth::user()->client_id)->where('id',$request->user_id)->first();

        $data['success'] = true;
        $data['user'] = $user;

        return Response::json($data,200,array());
    }

    public function storeUser(Request $request){
        $cre = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,        
        ];

        $rules = [
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
        ];

        if(!$request->has('id')){
            $cre['email'] = $request->email;   
            $cre['password'] = $request->password;
            $cre['confirm_password'] = $request->confirm_password;

            $rules['email'] = 'required|unique:users';
            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';

        }

        $validator = Validator::make($cre,$rules);

        if($validator->passes()){

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
            ];

            if($request->has('id')){

                DB::table('users')->where("client_id", Auth::user()->client_id)->where('id',$request->id)->update($data);
                
                $data['message'] = 'Successfully Update';

            }else{
                $data['password'] = Hash::make($request->password);
                $data['password_check'] = $request->password;
                $data['privilege'] = 1;
                $data['active'] = 1;
                $data["clint_id"] = Auth::user()->client_id;
                DB::table('users')->insert($data);

                $data['message'] = 'Successfully Added';
            }
            $data['success'] = true;

            $data['redirect_url'] = url('admin/users');

        }else{
            $data['success'] = false;
            $error = '';
            $messages = $validator->messages();
            foreach($messages->all() as $message){
                $error = $message;
                break;
            }
            $data['success'] = false;
            $data['message'] = $error;
          

        }

        
        return Response::json($data,200,array());
    }




   

    
}