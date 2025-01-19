<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Expense;

class ExpenseController extends Controller {

	public function index(Request $request){
		$sidebar = 'expenses';
        $subsidebar = 'expenses';
        return view('admin.expenses.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){

        $expenses = Expense::select('expenses.*','seasons.season',)
        ->leftJoin('seasons','expenses.season_id','=','seasons.year')->where("expenses.client_id", Auth::user()->client_id);

        if ($request->expense_account) {
            $expenses = $expenses->where('expenses.expense_account','=',$request->expense_account);
        }
        if ($request->expense_type) {
            $expenses = $expenses->where('expenses.expense_type','LIKE','%'.$request->expense_type.'%');
        }
        
        $expenses = $expenses->orderBy('expenses.date','ASC')->get();
        $data['success'] = true;
        $data['expense_accounts']= Expense::expenseAccounts();
        $data['expenses'] = $expenses;
        
        return Response::json($data,200,[]);
    }

    public function editForm($expense_id = 0){
        $sidebar= 'expenses';
        $subsidebar = 'expenses';
        return view('admin.expenses.add',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'expense_id'=>$expense_id,
        ]);
    }

    public function edit(Request $request){
        $data['expense_accounts']= Expense::expenseAccounts();

        if ($request->expense_id) {
            $expense=Expense::where("id", $request->expense_id)->where("client_id", Auth::user()->client_id)->first();
            if ($expense) {
                $expense->date = date('d-m-Y',strtotime($expense->date));
                $expense->attachment = $expense->expense_file;
            }
            $data['expense']=$expense;
        }
        $data['success'] = true;
        return Response::json($data,200,[]);
    }

    public function store(Request $request){
        if ($request->multiple_expense) {
            if (sizeof($request->multiple_expense)>0) {
                $multiple_expense = $request->multiple_expense;
                foreach ($multiple_expense as $single_expense) {
                    $cre = [
                        
                    ];
                    $rules=[
                        
                    ];
                    $validator = Validator::make($cre,$rules);
                    if ($validator->passes()) {
                        if (isset($single_expense['id'])) {
                            $expense = Expense::where("id", $single_expense['id'])->where("client_id", Auth::user()->client_id)->first();
                            $data['message']= 'Updated successfully';
                        }else{
                            $expense = new Expense;
                            $expense->client_id = Auth::user()->client_id;
                            $data['message']='Added successfully';
                        }

                        $expense->date = (isset($single_expense['date']))?date("Y-m-d",strtotime($single_expense['date'])):null;

                        if (isset($single_expense['date'])) { 
                            if (date("n",strtotime($single_expense['date']))<=3) {
                                $expense->season_id = date("Y",strtotime($single_expense['date']))-1;
                            }else{
                                $expense->season_id = date("Y",strtotime($single_expense['date']));
                            }
                        }

                        if(isset($single_expense["expense_season_id"])){
                            $expense->expense_season_id = $single_expense["expense_season_id"];
                        } else {
                            $expense->expense_season_id = $expense->season_id;
                        }
                        $expense->amount =(isset($single_expense['amount']))?$single_expense['amount']:null;
                        $expense->cgst =(isset($single_expense['cgst']))?$single_expense['cgst']:null;
                        $expense->sgst =(isset($single_expense['sgst']))?$single_expense['sgst']:null;
                        $expense->igst =(isset($single_expense['igst']))?$single_expense['igst']:null;
                        $expense->total_amount =(isset($single_expense['total_amount']))?$single_expense['total_amount']:null;
                        $expense->remarks =(isset($single_expense['remarks']))?$single_expense['remarks']:null;
                        $expense->expense_type =(isset($single_expense['expense_type']))?$single_expense['expense_type']:null;
                        $expense->expense_account =(isset($single_expense['expense_account']))?$single_expense['expense_account']:0;
                        $expense->vendor =(isset($single_expense['vendor']))?$single_expense['vendor']:"";
                        if (isset($single_expense['attachment'])) {
                            if ($single_expense['attachment'] !=null && $single_expense['attachment']) {
                                $expense->expense_file = $single_expense['attachment'];
                            }else{
                                $expense->expense_file =null;
                            }
                        }
                        $expense->save();

                        $data['success'] =true;
                        
                    }else{
                        $data['success']=false;
                        $data['message'] =$validator->errors()->first();
                    }
                }

            }else{
                $data['success'] =false;
                $data['message']='Please fill details';
            }
        }
        return Response::json($data,200,array());
    }

    public function delete($expense_id){
        $expense= Expense::where('id', $expense_id)->where("client_id", Auth::user()->client_id)->first();
        if ($expense) {
            $expense->delete();
            $data['success'] = true;
            $data['message'] = 'Expense deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Expense not found';
        }
        return Response::json($data,200,array());
    }



}


