@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')
	<div class="row">
        <div class="col-md-6">
            <h2 class="page-title">{{($income_id == 0)?'Add Income':'Update Income'}}</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('/admin/income')}}" class="btn btn-success" >Go Back</a>
        </div>
    </div>
    <div ng-controller="IncomeCtrl" ng-init="income_id={{(isset($income_id))?$income_id:''}};edit();">
        <form name="IncomeForm" novalidate ng-submit="onSubmit(IncomeForm.$valid)">
            <div ng-repeat="single_income in formData.multiple_income" style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>From / Customer</label>
                        <input type="text" ng-model="single_income.from" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Date</label>
                        <input type="text" ng-model="single_income.date" class="form-control datepicker">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Amount</label>
                        <input type="number" ng-model="single_income.amount" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Remarks</label>
                        <input type="text" ng-model="single_income.remarks" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Upload Income File</label><br>
                            <button type="button" ng-show="single_income.attachment == '' || single_income.attachment == null " class="btn btn-sm btn-secondary upload-btn" ngf-select="uploadFile($file,'attachment',single_income)" ladda="single_income.uploading" data-style="expand-right" >Attachment</button>
                                
                            <a class="btn btn-primary ng-cloak" href="{{url('/')}}/@{{single_income.attachment}}" ng-show="single_income.attachment != '' && single_income.attachment != null" target="_blank">View</a>

                            <a class="btn btn-danger ng-cloak" ng-click="single_income.attachment = '' " ng-show="single_income.attachment != '' && single_income.attachment != null "><i class="fa fa-remove"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 15px;">
                <a ng-if="income_id==0" href="javascript:;" ng-click="addMore()" class="btn btn-warning">Add More</a>
                <button type="submit" ladda="processing" class="btn btn-primary">Submit</button>
            </div>
        </form>


    </div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/incomes_ctrl.js?v='.$version)}}" ></script>

    
@endsection