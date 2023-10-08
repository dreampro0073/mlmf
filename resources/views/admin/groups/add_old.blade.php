@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};addGroupInit()">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="group_id == 0">Add</span>
                <span ng-if="group_id != 0">Edit</span> Groups
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups')}}" class="btn btn-info">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4">
      
        <div class="card-body">
            <form name="group" novalidate="novalidate" ng-submit="storeGroup(group.$valid)">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Group Name</label>
                        <input type="text" ng-model="formData.group_name" class="form-control" required />
                    </div>

                    <div class="form-group col-3" >
                        <label>Plan</label>
                        <selectize placeholder='Select Plan' ng-change="onChangePlan()" config="selectConfigPlans" options="plans" ng-model="formData.plan_id" required></selectize>
                    </div> 
                    <div class="col-md-2 form-group" ng-if="emi_type != 0">
                        <label>Start Date</label>
                        <select ng-model="formData.day" class="form-control" required ng-if="emi_type == 4">
                            <option value="">Day</option>
                            <?php for ($i=1; $i <=13 ; $i++) { 
                               ?>
                                <option ng-value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?> 

                            <?php for ($i=16; $i <=28 ; $i++) { 
                               ?>
                                <option ng-value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?> 

                        </select>
                        <select ng-model="formData.day" class="form-control" required ng-if="emi_type != 4">
                            <option value="">Day</option>
                            <?php for ($i=1; $i <=28 ; $i++) { 
                               ?>
                                <option ng-value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 form-group">
                        <label>Start Month</label>
                        <select ng-model="formData.month" class="form-control" required>
                            <option value="">Month</option>
                            <?php for ($i=1; $i <=12 ; $i++) { 
                               ?>
                                <option ng-value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Start Year</label>
                        <select ng-model="formData.year" class="form-control" required>
                            <option value="">Year</option>
                            <?php for ($i=2019; $i <=2023 ; $i++) { 
                               ?>
                                <option ng-value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="col-md-4 form-group">
                        <label>Block</label>
                        <select ng-model="formData.block_id" ng-change="getVillages()" class="form-control" required="" convert-to-number>
                            <option value="">--select--</option>
                            <option ng-repeat="item in blocks" value=@{{item.id}}>@{{ item.block_name}}</option>
                        </select>
                    </div> 

                    <div class="form-group col-4" >
                        <label>Village</label>
                        <selectize placeholder='Select Villages' config="selectConfigVillage" options="villages" ng-model="formData.village_id" required></selectize>
                    </div>

                    <div class="form-group col-4" >
                        <label>PIN Code</label>
                        <input type="text" minlength="6" maxlength="6" ng-model="formData.pin_code" class="form-control" required />
                    </div>
                    <div class="form-group col-4" >
                        <label>Processing Fees</label>
                        <input type="text" ng-model="formData.processing_fee" class="form-control" />
                    </div>
                    <div class="form-group col-4" >
                        <label>Insurance Fees</label>
                        <input type="text" ng-model="formData.insurance_fee" class="form-control" />
                    </div>

                    <div class="form-group col-4" >
                        <label>Customers</label>
                        
                        <input type="text" class="form-control" searchtype="customers" auto-complete  required />
                    </div>
                    <div class="col-md-12">
                        <div style="margin: 20px 0">
                            <div class="at-tag" ng-repeat="customer in formData.customers track by $index" ng-click="editCustomer(customer.id);">
                                <span>
                                    @{{ customer.name }}
                                </span>
                                <button class="btn btn-sm btn-danger" type="button" ng-click="removeCustomer($index)">X</button>
                            </div>
                        </div>
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
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_old_ctrl.js?v='.$version)}}" ></script>

    
@endsection