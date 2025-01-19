<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Income;
use App\Models\Expense;

class IncomeController extends Controller {

	public function index(Request $request){
		$sidebar = 'incomes';
        $subsidebar = 'incomes';
        return view('admin.incomes.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){

        $incomes = DB::table('incomes')->select('incomes.*','seasons.season')
        ->leftJoin('seasons','incomes.season_id','=','seasons.year')->where("client_id", Auth::user()->client_id);
        if ($request->from) {
            $incomes = $incomes->where('incomes.from','LIKE','%'.$request->from.'%');
        }
        
        $incomes = $incomes->orderBy('incomes.date','ASC')->get();
        $data['success'] = true;
        $data['expense_accounts']= Expense::expenseAccounts();
        $data['incomes'] = $incomes;
        
        return Response::json($data,200,[]);
    }

    public function editForm($income_id = 0){
        $sidebar= 'incomes';
        $subsidebar = 'incomes';
        return view('admin.incomes.add',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'income_id'=>$income_id,
        ]);
    }

    public function edit(Request $request){

        if ($request->income_id) {
            $income = DB::table('incomes')->where('id', $request->income_id)->where("client_id", Auth::user()->client_id)->first();
            if ($income) {
                $income->date = date('d-m-Y',strtotime($income->date));
                $income->attachment = $income->income_file;
            }
            $data['income'] = $income;
        }
        $data['success'] = true;
        return Response::json($data,200,[]);
    }

    public function store(Request $request){
        $client_id = Auth::user()->client_id;
        if ($request->multiple_income) {
            if (sizeof($request->multiple_income)>0) {
                $multiple_income = $request->multiple_income;
                foreach ($multiple_income as $single_income) {
                    $cre = [
                        
                    ];
                    $rules=[
                        
                    ];
                    $validator = Validator::make($cre,$rules);
                    if ($validator->passes()) {
                        if (isset($single_income['id'])) {
                            $income = Income::where('id',$single_income['id'])->where("client_id", $client_id)->first();
                            $data['message']= 'Updated successfully';
                        }else{
                            $income = new Income;
                            $data['message']='Added successfully';
                            $income->client_id = $client_id;
                        }
                        $income->from =(isset($single_income['from']))?$single_income['from']:0;
                        $income->date = (isset($single_income['date']))?date("Y-m-d",strtotime($single_income['date'])): null;
                        
                        if (isset($single_income['date'])) { 
                            if (date("n",strtotime($single_income['date']))<=3) {
                                $income->season_id = date("Y",strtotime($single_income['date']))-1;
                            }else{
                                $income->season_id = date("Y",strtotime($single_income['date']));
                            }
                        }

                        if (isset($single_income['attachment'])) {
                            if ($single_income['attachment'] !=null && $single_income['attachment']) {
                                $income->income_file = $single_income['attachment'];
                            }else{
                                $income->income_file =null;
                            }
                        }

                        $income->amount =(isset($single_income['amount']))?$single_income['amount']:null;
                        $income->remarks =(isset($single_income['remarks']))?$single_income['remarks']:null;
                        $income->save();

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

    public function delete($income_id){
        $income= Income::where('id', $income_id)->where("client_id", Auth::user()->client_id)->first();
        if ($income) {
            $income->delete();
            $data['success'] = true;
            $data['message'] = 'Income deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Income not found';
        }
        return Response::json($data,200,array());
    }



}


