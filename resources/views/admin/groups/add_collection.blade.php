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
                    <span>@{{customer.name}}</span><br>
                    <span ng-if='customer.old_balance > 0' style="color: red;">Old Balance : @{{customer.old_balance}} <br><button class="btn btn-sm btn-warning" ng-click="collectOldBalance(customer.customer_id)">Collect</span>
                    <!-- <span>@{{customer.aadhar_no}}</span> -->
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
                    <span ng-if="customer.is_enabled && !customer.emi_collected ">
                        <input type="checkbox" ng-checked="customer.emi_collected" ng-model="customer.is_checked" ng-click="addC(customer.emi_collection_id)" >

                        <span class="btn btn-sm btn-warning" ng-click="payPartEMI(customer.emi_collection_id)">Half Pay</span>

                    </span>
                    <span class="btn btn-danger" ng-click="payOldEMI(customer.emi_collection_id)" ng-if="!customer.emi_collected && !customer.future_emi && !customer.is_enabled">Not Paid</span>

                    <span ng-if="customer.emi_collected">Paid</span>
                    <button ng-if="!customer.emi_collected && customer.future_emi" ng-click="collectInAdvanced(customer.emi_collection_id)" class="btn btn-primary">Collect</button>
                    
                </td>
            </tr>
        </tbody>
    </table>
    <div class="mt-3">
        <button type="button" class="btn btn-primary" ng-click="saveCollecion();">Submit</button>
    </div>

    <div class="modal" id="payOldEMI-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Pending EMI</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>EMI Amount</label>
                            <input type="text" ng-model="formData.emi_amount" class="form-control" required readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Penalty Amount</label>
                            <input type="text" ng-model="formData.penalty_amount" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Give a valid Reason</label>
                            <textarea type="text" ng-model="formData.remark" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-info" ng-click="onSubmitPenalty()">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>     

    <div class="modal" id="payPartEMI-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay EMI in Parts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>EMI Amount</label>
                            <input type="text" ng-model="partData.emi_amount" class="form-control" required readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Paid Amount</label>
                            <input type="text" ng-model="partData.paid_amount" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Give a valid Reason</label>
                            <textarea type="text" ng-model="partData.remark" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-info" ng-click="onSubmitPart()">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div> 

</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>

    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>


@endsection