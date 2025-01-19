<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

class BankingController extends Controller {

    public function index(Request $request){
        $sidebar = 'banking';
        $subsidebar = 'banking';
        return view('admin.banking.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){
        $client_id = Auth::user()->client_id;
        $banking = DB::table('banking')->where("client_id",$client_id);
        if ($request->type) {
            $banking = $banking->where('banking.type',$request->type);
        }
        
        if ($request->transaction_type) {
            $banking = $banking->where('banking.transaction_type',$request->transaction_type);
        }
        
        if ($request->sent_received_by) {
            $banking = $banking->where('banking.sent_received_by','LIKE','%'.$request->sent_received_by.'%');
        }

        $banking = $banking->orderBy('banking.date','DESC')->get();
        
        foreach ($banking as $value) {
            $value->date = $value->date ? date("d-m-Y", strtotime($value->date)) : null;
        }
        $cash_expense = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 2)->where("type", 1)->sum("amount");
        $upi_expense = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 1)->where("type", 1)->sum("amount");
        $cash_income = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 2)->where("type", 2)->sum("amount");
        $upi_income = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 1)->where("type", 2)->sum("amount");
        
        $income = $cash_income + $upi_income;
        $expense = $cash_expense + $upi_expense;
        $upi_invest = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 1)->where("type", 3)->sum("amount");
        $cash_invest = DB::table("banking")->where("client_id",$client_id)->where("transaction_type", 2)->where("type", 3)->sum("amount");
        
        $invest = $upi_invest + $cash_invest;
        $balance = $income - $expense - $invest;
        $data['success'] = true;
        $data['banking'] = $banking;
        $data["cash_expense"] = $cash_expense;
        $data["upi_expense"] = $upi_expense;
        $data["cash_income"] = $cash_income;
        $data["upi_income"] = $upi_income;
        $data["income"] = $income;
        $data["expense"] = $expense;
        $data["balance"] = $balance;
        $data["upi_invest"] = $upi_invest;
        $data["cash_invest"] = $cash_invest;
        $data["invest"] = $invest;
        
        return Response::json($data,200,[]);
    }
 
    public function store(Request $request){

        $cre = [
            'amount'=>$request->amount,
            'added_by'=>Auth::id(),
            'type'=>$request->type,
            'transaction_type'=>$request->transaction_type,
            'sent_received_by'=>$request->sent_received_by,
        ];

        $rules = [
            'amount'=>"required",
            'added_by'=>"required",
            'type'=>"required",
            'transaction_type'=>"required",
            'sent_received_by'=>"required",
        ];

        $validator = Validator::make($cre,$rules);
        $invoice = null;
        if (isset($request->invoice)) {
            if ($request->invoice !=null && $request->invoice) {
                $invoice = $request->invoice;
            }else{
                $invoice = null;
            }
        }

        if($validator->passes()){
            $data['created_at'] = 
            $group_id = DB::table('banking')->insert([
                'amount'=>$request->amount,
                'added_by'=>Auth::id(),
                "client_id"=>Auth::user()->client_id,
                'type'=>$request->type,
                'date'=>date("Y-m-d", strtotime($request->date)),
                'transaction_type'=>$request->transaction_type,
                'sent_received_by'=>$request->sent_received_by,
                'remarks'=>$request->remarks,
                'invoice'=>$invoice,
                "created_at"=> date('Y-m-d H:i:s'),
            ]);

            $data['message'] = "Stored Successfully!";
            $data['success'] = true;
        }else{
            $data['message'] = $validator->errors();
            $data['success'] = false;
        }

        return Response::json($data, 200, []);
    }

} 
