@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="clientsCtrl" ng-init="client_id={{$client_id}};addClientInit()">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <span ng-if="client_id == 0">Add</span>
                <span ng-if="client_id != 0">Edit</span> Clients
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/clients')}}" class="btn btn-info">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4">
      
        <div class="card-body">
           <form name="client" novalidate="novalidate" ng-submit="storeClient(client.$valid)">
                <h1 class="h3 mb-2 text-gray-800">
                    <u>Clients Details</u>
                </h1>
                <div class="row">
                    <div class="col-md-12 form-group" ng-if="client_id > 0">
                        <label>Processing Status</label>
                        <select ng-model="formData.processing_status" class="form-control" >
                            <option value="">--Select--</option>
                            <option ng-value=2>Processing</option>
                            <option ng-value=3>Pending</option>
                            <option ng-value=1>Verified</option>
                            <option ng-value=4>Failed</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Client name</label>
                        <input type="text" ng-model="formData.name" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Fathers / Husband Name</label>
                        <input type="text" ng-model="formData.father_husband_name" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Date of birth</label>
                        <input type="text" ng-model="formData.dob" class="form-control datepicker" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Email</label>
                        <input type="email" ng-model="formData.email" class="form-control" />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Mobile</label>
                        <input type="number" maxlength="10" minlength="10"  ng-model="formData.mobile" class="form-control" required />
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Bank Name</label>
                        <select ng-model="formData.bank_id" class="form-control" >
                            <option value="">--select--</option>
                            <option ng-repeat="item in banks" ng-value=@{{item.id}}>@{{ item.bank_name}}</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>IFSC Code</label>
                        <input type="text" ng-model="formData.ifsc_code" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Account Number</label>
                        <input type="number" ng-model="formData.ac_no" class="form-control" required />
                    </div>

                    <div class="col-md-4 form-group">
                        <label>PIN Code</label>
                        <input type="number" maxlength="6" minlength="6" ng-model="formData.pin" class="form-control" required />
                    </div>


                    <div class="col-md-8 form-group">
                        <label>Address</label>
                        
                        <input type="text" ng-model="formData.address" class="form-control" required>
                    </div>


                    <!-- <div class="form-group col-4">
                        <label>State</label>
                        <selectize placeholder='Select State' ng-change="fetchDistricts()" config="selectConfig" options="states" ng-model="formData.state_id" required></selectize>

                    </div> -->
                    <div class="col-md-4 form-group">
                        <label>State</label>
                        <select ng-model="formData.state_id" class="form-control" convert-to-number ng-change="fetchDistricts()">
                            <option value="0">--select--</option>
                            <option ng-repeat="item in states" value=@{{item.id}}>@{{ item.state_name}}</option>
                        </select>
                    </div>


                    <div class="form-group col-4" ng-if="formData.state_id > 0">
                       <!--  <label>District</label>
                        <selectize placeholder='Select District' ng-change="getBlocks()" config="selectConfigDis" options="districts" ng-model="formData.district_id" required></selectize> -->
                        <label>District</label>
                        <select ng-model="formData.district_id" class="form-control" convert-to-number ng-change="getBlocks()">
                            <option value="0">--select--</option>
                            <option ng-repeat="item in districts" value=@{{item.id}}>@{{ item.city_name}}</option>
                        </select>
                    </div>                                      


                    <div class="col-md-4 form-group" ng-if="formData.district_id > 0">
                        <label>Block</label>
                        <select ng-model="formData.block_id" ng-change="getVillages()" class="form-control" required convert-to-number>
                            <option value="0">--select--</option>
                            <option ng-repeat="item in blocks" value=@{{item.id}}>@{{ item.block_name}}</option>
                        </select>
                    </div> 

                    <div class="form-group col-4" ng-if="formData.block_id > 0" >
                        <label>Village</label>
                        <!-- <selectize placeholder='Select Villages' config="selectConfigVillage" options="villages" ng-model="formData.village_id" required></selectize> -->
                        <select ng-model="formData.village_id" ng-change="getVillages()" class="form-control" required convert-to-number>
                            <option value="0">--select--</option>
                            <option ng-repeat="item in villages" value=@{{item.id}}>@{{ item.village_name}}</option>
                        </select>
                    </div>                                                          
                </div>

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label>Aadhaar Number</label>
                        <input type="number" maxlength="12" minlength="12" ng-model="formData.aadhaar_no" ng-readonly="client_id != 0" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Voter ID Number</label>
                        <input type="text" ng-model="formData.voter_id_no" class="form-control" />
                    </div>                    
                    <div class="col-md-4 form-group">
                        <label>PAN Number</label>
                        <input type="text" ng-model="formData.pan_no"  class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload Aadhaar Card</label><br>
                            <button type="button" ng-show="formData.aadhaar_card == '' || formData.aadhaar_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'aadhaar_card',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.aadhaar_card}}" ng-show="formData.aadhaar_card != '' && formData.aadhaar_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('aadhaar_card')" ng-show="formData.aadhaar_card != '' && formData.aadhaar_card != null ">x</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Voter ID Card</label><br>
                            <button type="button" ng-show="formData.voter_id_card == '' || formData.voter_id_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'voter_id_card',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.voter_id_card}}" ng-show="formData.voter_id_card != '' && formData.voter_id_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('voter_id_card')" ng-show="formData.voter_id_card != '' && formData.voter_id_card != null ">x</a>
                        </div>
                    </div>
                    

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload PAN Card</label><br>
                            <button type="button" ng-show="formData.pan_card == '' || formData.pan_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'pan_card',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.pan_card}}" ng-show="formData.pan_card != '' && formData.pan_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('pan_card')" ng-show="formData.pan_card != '' && formData.pan_card != null ">x</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload Client Photo</label><br>
                            <button type="button" ng-show="formData.customer_photo == '' || formData.customer_photo == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'customer_photo',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.customer_photo}}" ng-show="formData.customer_photo != '' && formData.customer_photo != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('customer_photo')" ng-show="formData.customer_photo != '' && formData.customer_photo != null ">x</a>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Bank Passbook</label><br>
                            <button type="button" ng-show="formData.bank_passbook == '' || formData.bank_passbook == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'bank_passbook',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.bank_passbook}}" ng-show="formData.bank_passbook != '' && formData.bank_passbook != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('bank_passbook')" ng-show="formData.bank_passbook != '' && formData.bank_passbook != null ">x</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Joint Photo</label><br>
                            <button type="button" ng-show="formData.joint_photo == '' || formData.joint_photo == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'joint_photo',formData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{formData.joint_photo}}" ng-show="formData.joint_photo != '' && formData.joint_photo != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeFile('joint_photo')" ng-show="formData.joint_photo != '' && formData.joint_photo != null ">x</a>
                        </div>
                    </div>
                                                          
                </div>
                <hr>
                <h1 class="h3 mb-2 text-gray-800">
                    <u>Guarantor Details</u>
                </h1>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Guarantor name</label>
                        <input type="text" ng-model="guarantorData.name" class="form-control" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Email</label>
                        <input type="email" ng-model="guarantorData.email" class="form-control" />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Mobile</label>
                        <input type="number" maxlength="10" minlength="10" ng-model="guarantorData.mobile" class="form-control" required />
                    </div>

                     <div class="col-md-4 form-group">
                        <label>Bank Name</label>
                        <select ng-model="guarantorData.bank_id" class="form-control" >
                            <option value="">--select--</option>
                            <option ng-repeat="item in banks" ng-value=@{{item.id}}>@{{ item.bank_name}}</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>IFSC Code</label>
                        <input type="text" ng-model="guarantorData.ifsc_code" class="form-control" />
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Account Number</label>
                        <input type="number" ng-model="guarantorData.ac_no" class="form-control" />
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label>Aadhaar Number</label>
                        <input type="number" maxlength="12" minlength="12" ng-model="guarantorData.aadhaar_no" class="form-control" required />
                    </div> 
                    <div class="col-md-4 form-group">
                        <label>Voter ID Number</label>
                        <input type="text" ng-model="guarantorData.voter_id_no" class="form-control" />
                    </div>                    
                    <div class="col-md-4 form-group">
                        <label>PAN Number</label>
                        <input type="text" ng-model="guarantorData.pan_no" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload Aadhaar card</label><br>
                            <button type="button" ng-show="guarantorData.aadhaar_card == '' || guarantorData.aadhaar_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'aadhaar_card',guarantorData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{guarantorData.aadhaar_card}}" ng-show="guarantorData.aadhaar_card != '' && guarantorData.aadhaar_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeGuarantorFile('aadhaar_card')" ng-show="guarantorData.aadhaar_card != '' && guarantorData.aadhaar_card != null ">x</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Voter ID Card</label><br>
                            <button type="button" ng-show="guarantorData.voter_id_card == '' || guarantorData.voter_id_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'voter_id_card',guarantorData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{guarantorData.voter_id_card}}" ng-show="guarantorData.voter_id_card != '' && guarantorData.voter_id_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeGuarantorFile('voter_id_card')" ng-show="guarantorData.voter_id_card != '' && guarantorData.voter_id_card != null ">x</a>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload PAN card</label><br>
                            <button type="button" ng-show="guarantorData.pan_card == '' || guarantorData.pan_card == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'pan_card',guarantorData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{guarantorData.pan_card}}" ng-show="guarantorData.pan_card != '' && guarantorData.pan_card != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeGuarantorFile('pan_card')" ng-show="guarantorData.pan_card != '' && guarantorData.pan_card != null ">x</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Upload Photo</label><br>
                            <button type="button" ng-show="guarantorData.photo == '' || guarantorData.photo == null " class="btn btn-sm btn-info" ngf-select="uploadFile($file,'photo',guarantorData)" data-style="expand-right" >Upload</button>
                                
                            <a class="btn btn-warning btn-sm ng-cloak" href="{{url('/')}}/@{{guarantorData.photo}}" ng-show="guarantorData.photo != '' && guarantorData.photo != null" target="_blank">View</a>

                            <a class="btn btn-danger btn-sm ng-cloak" ng-click="removeGuarantorFile('photo')" ng-show="guarantorData.photo != '' && guarantorData.photo != null ">x</a>
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
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection