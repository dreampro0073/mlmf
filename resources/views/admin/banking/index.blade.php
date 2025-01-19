@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="BankCtrl" ng-init="init();" >
		<div class="page-title-cont">
	        <div class="row">
	            <div class="col-md-8">
	                <h2 class="page-title">Transaction Statement</h2>
	            </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-sm btn-info" ng-click="addNew()">Add</button>
                </div>
	        </div>
	    </div>
        <div style="margin-bottom: 20px;padding-bottom: 20px;border-bottom:  1px solid #555;">  
            <div>
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>Cash Expense</td>
                        <td>UPI Expense</td>
                        <td>Cash Income</td>
                        <td>UPI Income</td>
                        <td>Cash Invest</td>
                        <td>UPI Invest</td>
                        <th>Expense</th>
                        <th>Invest</th>
                        <th>Income</th>
                        <th>Balance</th>
                    </tr>
                    <tr>
                        <th>@{{cash_expense}}</th>
                        <th>@{{upi_expense}}</th>
                        <th>@{{cash_income}}</th>
                        <th>@{{upi_income}}</th>
                        <th>@{{cash_invest}}</th>
                        <th>@{{upi_invest}}</th>
                        <th>@{{expense}}</th>
                        <th>@{{invest}}</th>
                        <th>@{{income}}</th>
                        <th>
                            <span ng-if="balance > 0" style="color: green;">@{{balance}}</span>
                            <span ng-if="balance <= 0" style="color: red;">@{{balance}}</span>
                        </th>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label>Type</label>
                    <select ng-model="searchData.type" class="form-control" >
                        <option value="">--select--</option>
                        <option ng-value="1">Expense</option>
                        <option ng-value="2">Income</option>
                        <option ng-value="3">Invest</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label>Transaction Type</label>
                    <select ng-model="searchData.transaction_type" class="form-control" >
                        <option value="">--select--</option>
                        <option ng-value="1">UPI</option>
                        <option ng-value="2">Cash</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Sent / Received By</label>
                    <input type="text" class="form-control" ng-model="searchData.sent_received_by">
                </div>
                
                <div class="col-md-3 " style="margin-top: 34px;">
                    <button type="submit" ng-click="init()" ladda="loading" class="btn btn-sm btn-primary">Search</button>
                    <button type="submit" ng-click="clearFilter()" ladda="loading" class="btn btn-sm btn-warning">Clear</button>

                </div>
            </div>
        </div>
        
        <table class="table table-condensed table-bordered" >
            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Sent / Received By</th>
                    <th>Transaction Type</th>
                    <th>Invoice</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="banking.length>0" ng-repeat="item in banking">
                    <td>@{{$index+1}}</td>
                    <td>@{{item.date}}</td>
                    <td>@{{item.amount}}</td>
                    <td>
                        <span ng-if="item.type == 1">Expense</span>
                        <span ng-if="item.type == 2">Income</span>
                        <span ng-if="item.type == 3">Invest</span>
                    </td>
                    <td>@{{item.sent_received_by}}</td>
                    <td>
                        <span ng-if="item.transaction_type == 1">UPI</span>
                        <span ng-if="item.transaction_type == 2">Cash</span>
                    </td>
                    <td>
                        <div ng-if="item.invoice != '' && item.invoice != null">
                            <a href="{{url('/')}}/@{{item.invoice}}" target="_blank">View</a>
                        </div>
                    </td>
                    <td style="font-size: 11px">@{{item.remarks}}</td>
                </tr>
            </tbody>
        </table>

        <div ng-if="banking.length == 0" class="alert alert-warning">Data Not Available !</div>

        <div id="addModal" class="modal custom-modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Add New Transaction</h4>
                        <button type="btn" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form name="Transaction" novalidate="novalidate" ng-submit="storeTransaction(Transaction.$valid)">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Amount</label>
                                    <input type="text" ng-model="formData.amount" class="form-control" required />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Type</label>
                                    <select ng-model="formData.type" class="form-control" required >
                                        <option value="">--Select--</option>
                                        <option ng-value=1>Expense</option>
                                        <option ng-value=2>Income</option>
                                        <option ng-value=3>Invest</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 form-group">
                                    <label>Transaction type</label>
                                    <select ng-model="formData.transaction_type" class="form-control" required >
                                        <option value="">--Select--</option>
                                        <option ng-value=1>UPI</option>
                                        <option ng-value=2>CASH</option>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label>Date</label>
                                    <input type="text" ng-model="formData.date" class="form-control datepicker" required />
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Sent / Received By</label>
                                    <input type="text" ng-model="formData.sent_received_by" class="form-control" required />
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Upload Invoice</label><br>
                                        <button type="button" ng-show="formData.invoice == '' || formData.invoice == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'invoice',formData)" data-style="expand-right" >Upload</button>
                                            
                                        <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.invoice}}" ng-show="formData.invoice != '' && formData.invoice != null" target="_blank">View</a>

                                        <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('invoice')" ng-show="formData.invoice != '' && formData.invoice != null ">x</a>
                                    </div>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label>Remarks</label>
                                    <textarea ng-model="formData.remarks" class="form-control"></textarea>
                                </div>
                    
                            </div>                    
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary" ng-disabled="loading">
                                    <span ng-if="!loading">Submit</span>
                                    <span ng-if="loading" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                </button> 
                            </div>  
                            
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <?php $version = "0.0.4"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/bank_ctrl.js?v='.$version)}}" ></script>

    
@endsection