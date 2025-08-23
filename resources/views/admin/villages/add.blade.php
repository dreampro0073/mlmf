<?php $version=env('JS_VERSION'); ?>
@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="villageCtrl" ng-init="village_id={{$village_id}};addVillageInit();getStates();">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="village_id == 0">Add</span>
                <span ng-if="village_id != 0">Edit</span> Village
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/blocks')}}" class="btn btn-info">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4">
      
        <div class="card-body">
            <form name="group" novalidate="novalidate" ng-submit="storeBlock(group.$valid)">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>State</label>
                        <select ng-model="formData.state_id" class="form-control" convert-to-number ng-change="fetchDistricts()">
                            <option value="0">--select--</option>
                            <option ng-repeat="item in states" value=@{{item.id}}>@{{ item.state_name}}</option>
                        </select>
                    </div>
                    <div class="form-group col-4" ng-if="formData.state_id > 0">  
                        <label>District</label>
                        <select ng-model="formData.city_id" class="form-control" convert-to-number ng-change="getBlocks()">
                            <option value="0">--select--</option>
                            <option ng-repeat="item in districts" value=@{{item.id}}>@{{ item.city_name}}</option>
                        </select>
                    </div>                                      


                    <div class="col-md-4 form-group" ng-if="formData.city_id > 0">
                        <label>Block</label>
                        <select ng-model="formData.block_id" class="form-control" required convert-to-number>
                            <option value="0">--select--</option>
                            <option ng-repeat="item in blocks" value=@{{item.id}}>@{{ item.block_name}}</option>
                        </select>
                    </div> 
                   
                    <div class="col-md-4 form-group">
                        <label>Village Name</label>
                        <input type="text" ng-model="formData.village_name" class="form-control" required />
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
    <script type="text/javascript" src="{{url('assets/scripts/core/village_ctrl.js?v='.$version)}}" ></script>
@endsection