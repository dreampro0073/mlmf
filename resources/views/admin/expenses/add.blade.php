@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')
	<div class="row">
        <div class="col-md-6">
            <h2 class="page-title">{{($expense_id == 0)?'Add Expense':'Update Expense'}}</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('/admin/expenses')}}" class="btn btn-success" >Go Back</a>
        </div>
    </div>
    <div ng-controller="ExpenseCtrl" ng-init="expense_id={{(isset($expense_id))?$expense_id:''}};edit();">
        <form name="ExpenseForm" ng-submit="onSubmit(ExpenseForm.$valid)" novalidate="novalidate">
            <div ng-repeat="single_expense in formData.multiple_expense track by $index" style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label>Date</label>
                        <input type="text" class="form-control datepicker" ng-model="single_expense.date">
                    </div>

                    @if($expense_id != 0)
                    <div class="col-md-2 form-group">
                        <label>Expense Season ID</label>
                        <input type="number" class="form-control" ng-model="single_expense.expense_season_id">
                    </div>
                    @endif

                    <div class="col-md-2 form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" ng-model="single_expense.amount">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>CGST</label>
                        <input type="number" class="form-control" ng-model="single_expense.cgst">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>SGST</label>
                        <input type="number" class="form-control" ng-model="single_expense.sgst">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>IGST</label>
                        <input type="number" class="form-control" ng-model="single_expense.igst">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Total Amount</label>
                        <span class="form-control">@{{single_expense.total_amount=single_expense.amount+single_expense.cgst+single_expense.sgst+single_expense.igst}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-2 form-group">
                        <label>Expense Type</label>
                        <input type="text" class="form-control" ng-model="single_expense.expense_type">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Expense Account</label>
                        <select class="form-control" convert-to-number ng-model="single_expense.expense_account">
                            <option value="">Select</option>
                            <option ng-repeat="(key,value) in expense_accounts" value="@{{key}}">@{{value}}</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Vendor Names</label>
                        <input type="text" class="form-control" ng-model="single_expense.vendor">
                    </div>
                    <div class="col-md-5 form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" ng-model="single_expense.remarks">
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Upload Expense File</label><br>
                            <button type="button" ng-show="single_expense.attachment == '' || single_expense.attachment == null " class="btn btn-sm btn-secondary upload-btn" ngf-select="uploadFile($file,'attachment',single_expense)" ladda="single_expense.uploading" data-style="expand-right" >Attachment</button>
                                
                            <a class="btn btn-primary ng-cloak" href="{{url('/')}}/@{{single_expense.attachment}}" ng-show="single_expense.attachment != '' && single_expense.attachment != null" target="_blank">View</a>

                            <a class="btn btn-danger ng-cloak" ng-click="single_expense.attachment = '' " ng-show="single_expense.attachment != '' && single_expense.attachment != null "><i class="fa fa-remove"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div style="margin-top: 10px;">
                @if($expense_id ==0)
                    <a href="javascript:;" class="btn btn btn-success" ng-click="duplicate()">Duplicate</a>
                @endif
                <button type="submit" class="btn btn-primary" ladda="processing">Submit</button>   
            </div>
            <div style="margin-top: 15px;">
                
            </div>
        </form>

    </div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/expenses_ctrl.js?v='.$version)}}" ></script>

    
@endsection