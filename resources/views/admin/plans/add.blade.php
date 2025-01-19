@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="plansCtrl" ng-init="plan_id={{$plan_id}}; addPlanInit()">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="client_id == 0">Add</span>
                <span ng-if="client_id != 0">Edit</span> Plan
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/plans')}}" class="btn btn-info">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4">
      
        <div class="card-body">
           <form name="plan" novalidate="novalidate" ng-submit="storePlan(plan.$valid)">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Plan Name</label>
                        <input type="text" ng-model="formData.plan_name" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Principle Amount</label>
                        <input type="number" ng-model="formData.principal_amount" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Interest rate </label>
                        <input type="text" ng-model="formData.interest_rate" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Time line (in day's)</label>
                        <input type="text" ng-model="formData.time_line" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>EMI type</label>
                        <select ng-model="formData.emi_type" class="form-control" required >
                            <option value="">--select--</option>
                            <option ng-repeat="item in emi_types" value=@{{item.id}}>@{{ item.type_name}}</option>
                        </select>
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
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/plan_ctrl.js?v='.$version)}}" ></script>

    
@endsection