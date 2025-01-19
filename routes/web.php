<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\GroupsOldController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\BankingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [UserController::class,'login'])->name("login");
Route::get('/test', [UserController::class,'testx']);
Route::get('/emiCalculator', [PlanController::class,'emiCalculator']);
Route::post('/login', [UserController::class,'postLogin']);


Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});


Route::get('emi-calc',[AdminController::class,'test']);

Route::group(['middleware'=>'auth'],function(){
	Route::group(['prefix'=>"admin"], function(){
		Route::get('/dashboard',[AdminController::class,'dashboard']);
		Route::post('/uploadFile',[AdminController::class,'uploadFile']);
		Route::get('/group-delete/{group_id}', [AdminController::class,'deleteGroup']);

		Route::group(['prefix'=>"plans"], function(){
			Route::get('/',[PlanController::class,'index']);
			Route::get('/test',[PlanController::class,'index']);
			Route::get('/add/{plan_id?}',[PlanController::class,'addPlan']);
			Route::get('/delete/{plan_id}',[PlanController::class,'deletePlan']);
		});
		Route::group(['prefix'=>"clients"], function(){
			Route::get('/',[ClientsController::class,'index']);
			Route::get('/add/{client_id?}',[ClientsController::class,'addClient']);
			Route::get('/details/{client_id?}',[ClientsController::class,'clientDetails']);
			Route::get('/history/{client_id?}',[ClientsController::class,'historyDetails']);
			Route::get('/delete/{client_id}',[ClientsController::class,'deleteClient']);
		});

		Route::group(['prefix'=>"groups"], function(){
			Route::get('/',[GroupsController::class,'index']);
			Route::get('/add/{group_id?}',[GroupsController::class,'addGroup']);
			Route::get('/view/{group_id}',[GroupsController::class,'viewGroup']);
			Route::get('/today/collection/{group_id?}',[GroupsController::class,'todayCollection']);
			Route::get('/add-collection/{group_id?}',[GroupsController::class,'addCollection']);
			Route::get('/delete/{group_id}',[GroupsController::class,'deleteGroup']);
			Route::get('/emi-status/{group_id}',[GroupsController::class,'emiStatus']);
			Route::get('/loan-card/{id}',[GroupsController::class,'loanCard']);
			Route::get('/c-loan-card/{group_id}/{customer_id}',[GroupsController::class,'cLoanCard']);
			Route::get('/print-loan-card/{group_id}/{customer_id}',[GroupsController::class,'printLoanCard']);
			Route::get('/shapat-patra/{group_id}/{customer_id}',[GroupsController::class,'shapatPatra']);
			Route::get('/print-collection-view',[GroupsController::class,'printTodayCollectionInit']);
			Route::get('/close-group/{id}/{enc_id}',[GroupsController::class,'closeGroup']);

		});

		Route::group(["prefix"=>"expenses"],function(){
			Route::get('/',[ExpenseController::class,'index']);
			Route::get('/add',[ExpenseController::class,'editForm']);
			Route::get('/edit/{expense_id}',[ExpenseController::class,'editForm']);
		});		

		Route::group(["prefix"=>"income"],function(){
			Route::get('/',[IncomeController::class,'index']);
			Route::get('/add',[IncomeController::class,'editForm']);
			Route::get('/edit/{income_id}',[IncomeController::class,'editForm']);
		});

		Route::group(["prefix"=>"banking"],function(){
			Route::get('/',[BankingController::class,'index']);
			Route::get('/add',[BankingController::class,'addForm']);
		});

	});
});



Route::group(['prefix'=>"api"], function(){
	Route::post('/districts',[ClientsController::class,'getDistricts']);
	Route::post('/blocks',[ClientsController::class,'getBlocks']);
	Route::post('/villages',[ClientsController::class,'geVtillages']);
	Route::get('/search-customers',[GroupsController::class,'searchCustomers']);

	Route::group(['prefix'=>"plans"], function(){
		Route::post('/init',[PlanController::class,'planInit']);
		Route::post('/store',[PlanController::class,'planStore']);
		
		Route::post('/view-plan',[PlanController::class,'viewPlan']);

	});
	
	Route::group(['prefix'=>"dashboard"], function(){
		Route::post('/pending-list',[AdminController::class,'pendingList']);
	});

	Route::group(['prefix'=>"clients"], function(){
		Route::post('/init',[ClientsController::class,'clientInit']);
		Route::post('/details',[ClientsController::class,'clientDetailsInit']);
		Route::post('/store',[ClientsController::class,'clientStore']);
	});

	Route::group(['prefix'=>"groups"], function(){
		Route::post('/init',[GroupsController::class,'groupInit']);
		Route::post('/init-view',[GroupsController::class,'groupViewInit']);
		Route::post('/collection-view',[GroupsController::class,'todayCollectionInit']);
		Route::post('/view-collection',[GroupsController::class,'viewCollection']);
		Route::post('/store',[GroupsController::class,'groupStore']);
		Route::post('/save-collection',[GroupsController::class,'saveCollection']);
		Route::post('/emi-status',[GroupsController::class,'groupEMIInit']);
		Route::post('/collection/pay',[GroupsController::class,'payEMI']);
		Route::post('/collection/paid',[GroupsController::class,'paidEMI']);
		Route::post('/get-loan-card',[GroupsController::class,'getLoanCard']);
		Route::post('/get-cloan-card',[GroupsController::class,'getCLoanCard']);
		Route::post('/activate-group',[GroupsController::class,'actvateGroup']);
		Route::post('/store-purpose/{group_customer_id}',[GroupsController::class,'storePurpose']);

		Route::post('/select-plan',[GroupsController::class,'getPlanType']);
		
		Route::post('/get-penalty',[GroupsController::class,'getPenalty']);
		Route::post('/store-penalty',[GroupsController::class,'storePenalty']);
		Route::post('/advanced-collect',[GroupsController::class,'advancedCollect']);
		
		Route::post('/update-invoice',[GroupsController::class,'updateInvoice']);
	});

	Route::group(["prefix"=>"expenses"],function(){
		Route::post('/init',[ExpenseController::class,'init']);
		Route::post('/edit',[ExpenseController::class,'edit']);
		Route::post('/store',[ExpenseController::class,'store']);
		Route::get('/delete/{expense_id}',[ExpenseController::class,'delete']);
	});	

	Route::group(["prefix"=>"income"],function(){
		Route::post('/init',[IncomeController::class,'init']);
		Route::post('/edit',[IncomeController::class,'edit']);
		Route::post('/store',[IncomeController::class,'store']);
		Route::get('/delete/{income_id}',[IncomeController::class,'delete']);
	});

	Route::group(["prefix"=>"banking"],function(){
		Route::post('/init',[BankingController::class,'init']);
		Route::post('/store',[BankingController::class,'store']);
	});

	
	Route::post('/emi-part',[GroupsController::class,'EMIPart']);
	Route::post('/old-collect',[GroupsController::class,'oldCollect']);

});

Route::group(['prefix'=>"old-groups"], function(){
	Route::get('/add/{group_id?}',[GroupsOldController::class,'addGroup']);
	Route::post('/store',[GroupsOldController::class,'groupStore']);
	Route::post('/activate-group',[GroupsOldController::class,'actvateGroup']);
});
