@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="IncomeCtrl" ng-init="init();" >
		<div class="page-title-cont">
	        <div class="row">
	            <div class="col-md-8">
	                <h2 class="page-title">Income</h2>
	            </div>
                <div class="col-md-4 text-right">
                    <a href="{{url('admin/income/add')}}" class="btn btn-info">Add</a>
                </div>
	        </div>
	    </div>
        <div style="margin-bottom: 20px;padding-bottom: 20px;border-bottom:  1px solid #555;">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From</label>
                    <input type="text" class="form-control" ng-model="searchData.from" ng-change="onSearch()">
                </div>
                
                <div class="col-md-2 " style="margin-top: 34px;">
                    <button type="submit" ng-click="clearFilter()" class="btn btn-sm btn-warning">Clear</button>

                </div>
            </div>
        </div>
        
        <table class="table table-condensed table-bordered" >
            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Attachment</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="incomes.length>0" ng-repeat="income in incomes" >
                    <td>@{{$index+1}}</td>
                    <td >@{{income.date|date:'dd-MM-yyyy'}}</td>
                    <td>@{{income.from}}</td>
                    <td>@{{income.amount}}</td>
                    <td style="font-size: 11px">@{{income.remarks}}</td>
                    <td>
                        <a href="{{ url('/') }}/@{{income.income_file}}" target="_blank" ng-if="income.income_file">View</a>
                    </td>
                    <td style="text-align: center;">
                        <button ng-click="viewIncome($index)" class="btn btn-sm btn-primary">View</button>
                        <a href="{{url('/admin/income/edit')}}/@{{income.id}}" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" ng-click="deleteIncome(income,$index)">Delete</i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div ng-if="incomes.length == 0" class="alert alert-warning">Data Not Available !</div>
        <div>
        <!-- Modal -->
            <div id="myModal" class="modal custom-modal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Income Details</h4>
                            <button type="btn" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>Date</strong></td>
                                        <td>@{{income.date|date:'dd-MM-yyyy'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Season</strong></td>
                                        <td>@{{income.season}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount</strong></td>
                                        <td>@{{income.amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount</strong></td>
                                        <td>@{{income.amount}}</td>
                                    </tr>
                                    
                                    <tr ng-if="income.income_file != null">
                                        <td><strong>Income File</strong></td>
                                        <td>
                                            <a href="../@{{income.income_file}}" target="_blank">View File</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/incomes_ctrl.js?v='.$version)}}" ></script>

    
@endsection