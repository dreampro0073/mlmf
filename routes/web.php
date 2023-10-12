<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\GroupsOldController;


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
Route::get('/test', [UserController::class,'test']);
Route::get('/emiCalculator', [PlanController::class,'emiCalculator']);
Route::post('/login', [UserController::class,'postLogin']);


Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});


Route::get('emi-calc',[AdminController::class,'test']);

Route::group(['prefix'=>"admin"], function(){
	Route::get('/dashboard',[AdminController::class,'dashboard']);
	Route::post('/uploadFile',[AdminController::class,'uploadFile']);
	


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
	});
});

Route::group(['prefix'=>"old-groups"], function(){
	Route::get('/add/{group_id?}',[GroupsOldController::class,'addGroup']);
	Route::post('/store',[GroupsOldController::class,'groupStore']);
	Route::post('/activate-group',[GroupsOldController::class,'actvateGroup']);
});
