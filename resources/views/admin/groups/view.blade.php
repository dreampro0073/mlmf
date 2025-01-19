@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};viewGroupInit()">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="group_id > 0">@{{groups_details.group_name}} - @{{groups_details.village_name}}  (@{{groups_details.pin_code}})</span>
                <span ng-if="group_id == 0">Groups Collection Status </span>
            </h1>
        </div>
        <div class="col-md-4 text-right">
            <a ng-if="groups_details.active == 1" href="{{url('admin/groups/add-collection/'.$group_id)}}" class="btn btn-primary">Add Collection</a>
            <a href="{{url('admin/groups')}}" class="btn btn-info">Back</a>
        </div>
    </div>    
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Customer</th>
                <th>Aadhar</th>
                <th>Purpose</th>
                <th>Invoice</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="customer in groups_details.customers track by $index">
                <td>
                    @{{ $index+1 }}
                </td>
                <td>
                    <a href="{{url('admin/clients/details/')}}/@{{customer.enc_id}}" target="_blank">
                        @{{customer.name}}
                    </a>
                </td>
                <td>
                    @{{customer.aadhaar_no}}
                </td>
                <td>
                    @{{customer.purpose}}
                </td>
                <td>
                    <button type="button" ng-show="customer.invoice == '' || customer.invoice == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'invoice',customer)" data-style="expand-right" >Upload</button>
                        
                    <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{customer.invoice}}" ng-show="customer.invoice != '' && customer.invoice != null" target="_blank">View</a>

                    <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('customer')" ng-show="customer.invoice != '' && customer.invoice != null ">x</a>
                </td>

                <td>
                    <a ng-if="groups_details.active == 1" href="{{url('admin/groups/c-loan-card')}}/@{{group_id}}/@{{customer.id}}" class="btn btn-sm btn-success">Loan Card</a>
                    <a ng-if="groups_details.active == 1" href="{{url('admin/groups/shapat-patra')}}/@{{group_id}}/@{{customer.id}}" class="btn btn-sm btn-primary">
                        Shapat Patra
                    </a>

                    <button class="btn btn-sm btn-warning" type="button" ng-click="openPurpsoeModal(customer.group_customer_id,customer.purpose);">
                        Purpose
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="modal" id="purpose-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Purpose</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" ng-model="purpose" class="form-control">
                    </div>

                    <div class="text-right">
                        <button type="button" ng-disabled="purpose_loading" class="btn btn-primary" ng-click="submitPurpose();">
                            <span ng-if="!purpose_loading">Submit</span>
                            <span ng-if="purpose_loading" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        </button>
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