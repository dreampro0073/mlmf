@extends('admin.layout')


@section('header_scripts')
 
@endsection

@section('main')

<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};getLoanCard();">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Loan Card</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups')}}" class="btn btn-primary">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4"> 
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" width="100%" cellspacing="0">
                    <thead>
                       <tr>
                            <th>Numbers of EMI</th>
                            <th>Date</th>
                            <th>EMI</th>
                            <th>Interest Payment</th>
                            <th>Principal Payment</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in group.group_dates track by $index">
                            <td>@{{$index+1}}</td>
                            <td>@{{item.emi_date}}</td>
                            <td>@{{item.emi_amount}}</td>
                            <td>@{{item.interest_payment}}</td>
                            <td>@{{item.principal_payment}}</td>   
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-danger" ng-if="group.group_dates.length == 0">Data Not Found!</div>
            </div>
        </div>
    </div>  
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection