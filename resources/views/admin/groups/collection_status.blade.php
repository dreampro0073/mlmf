@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};collectionInit()">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="group_id > 0">@{{groups_details[0].group_name}} - @{{groups_details[0].village_name}}</span>
                <span ng-if="group_id == 0">Groups Collection Status</span>
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups/print-collection-view')}}" class="btn btn-sm btn-warning">Print Demand</a>
            <a href="{{url('admin/groups')}}" class="btn btn-sm btn-info">Back</a>
        </div>
    </div>    
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Customer</th>
                <th>Mobile</th>
                <th>Aadhar</th>
               
                <th>EMI Amount</th>
            </tr>
        </thead>
        <tbody ng-repeat="group_date in group_dates">
            <tr>
                <th colspan="4">
                    Group : <a href="{{url('admin/groups/view/')}}/@{{group_date.group_id}}" target="_blank">
                         @{{group_date.group_name}}
                    </a>
                </th>
                <th class="pull-right">
                    <a href="{{url('admin/groups/add-collection/')}}/@{{group_date.group_id}}" class="btn btn-sm btn-primary">Add Collection</a>
                </th>
            </tr>
            <tr ng-repeat="customer in group_date.group_customers track by $index">
                <td>
                    @{{ $index+1 }}
                </td>
                <td>
                    <a href="{{url('admin/clients/details/')}}/@{{customer.enc_id}}" target="_blank">
                        @{{customer.name}}
                    </a>
                </td>
                 <td>
                    @{{customer.mobile}}
                </td>
                <td>
                    @{{customer.aadhaar_no}}
                </td>
                
                <td>
                    @{{group_date.emi_amount}}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="alert alert-danger" ng-if="group_dates.length == 0">Data Not Found!</div>
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection