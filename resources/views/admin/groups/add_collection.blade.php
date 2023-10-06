@extends('admin.layout')

@section('header_scripts')

@endsection

@section('main')
<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};viewCollection();">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                Add Collection
            </h1>
            <p>
                @{{group.group_name}} - @{{group.village_name}}
            </p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups/view/'.$group_id)}}" class="btn btn-info">Back</a>
        </div>
    </div>

    <table ng-if="!c_loading" class="table table-bordered table-striped">
        <thead>
            <tr>
                <td style="width: 50px;">
                    <span>Sn</span>
                </td>
                <td style="width: 120px;">
                    <span>Date</span>
                </td>
                <td ng-repeat="customer in group_customers">
                    <span>@{{customer.name}}</span>
                    <span>@{{customer.aadhar_no}}</span>
                </td>
            </tr>

        </thead>
        <tbody>
            <tr ng-repeat="item in group.group_dates track by $index">
                <td>@{{$index+1}}</td>
                <td>
                    @{{item.emi_date}}
                </td>
                <td ng-repeat="customer in item.customers">
                    <span ng-if="customer.is_enabled ">
                        <input type="checkbox" ng-checked="customer.emi_collected" ng-model="customer.is_checked" ng-click="addC(customer.emi_collection_id)" >

                    </span>
                    <span  ng-if="!customer.emi_collected && !customer.future_emi && !customer.is_enabled">Not Paid</span>

                    <span ng-if="customer.emi_collected">Paid</span>
                    <span ng-if="!customer.emi_collected && customer.future_emi">Future EMI</span>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="mt-3">
        <button type="button" class="btn btn-primary" ng-click="saveCollecion();">Submit</button>
    </div>
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>

    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>


@endsection