 @extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="clientsCtrl" ng-init="client_id={{$client_id}};clientDetails()">
    <div class="mb-4 row mt-3">
        <div class="col-md-6">
            <div class="table-div pro-div">
                <div class="image">
                    <img ng-if="client.customer_photo" src="{{url('/')}}/@{{client.customer_photo}}" style="width:120px;height:120px;border-radius: 50%;margin-right: 24px;">
                    
                </div>
                <div class="info">
                    <h3 class="name">
                         @{{client.name}}
                    </h3>
                </div>
            </div>
            
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/clients/add/')}}/@{{client.enc_id}}" class="btn btn-primary btn-sm">Edit</a> 
            <a href="{{url('admin/clients/history/')}}/@{{client.enc_id}}" class="btn btn-warning btn-sm">History</a> 
        </div>
    </div>
    <hr>
    <div class="row">
        
        <div class="col-md-4">
            <div class="profile-div">
                <span>Name</span>
                <label>@{{client.name}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Fathers / Husband Name</span>
                <label>@{{client.father_husband_name}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Date of Bith</span>
                <label>@{{client.dob}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Email</span>
                <label>@{{client.email}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Mobile</span>
                <label>@{{client.mobile}}</label>
            </div>
        </div>
    </div>
    <u>Bank Details</u>
    <hr>
    <div class="row">

        <div class="col-md-4">
            <div class="profile-div">
                <span>Bank Name</span>
                <label>@{{client.bank_name}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>IFSC Code</span>
                <label>@{{client.ifsc_code}}</label>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>Bank Account</span>
                <div>
                    <label>@{{client.ac_no}}</label>
                    <a ng-if="client.bank_passbook" href="{{url('/')}}/@{{client.bank_passbook}}" class="btn btn-sm btn-primary" target="_blank">view</a>
                </div>
            </div>
        </div>

    </div>
    <u>Documents</u>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-div">
                <span>Aadhaar</span>
                <div>
                    <label>@{{client.aadhaar_no}}</label>
                    <a ng-if="client.aadhaar_card" href="{{url('/')}}/@{{client.aadhaar_card}}" class="btn btn-sm btn-primary" target="_blank">view</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>PAN</span>
                <div>
                    <label>@{{client.pan_no}}</label>
                    <a ng-if="client.pan_card" href="{{url('/')}}/@{{client.pan_card}}" target="_blank"  class="btn btn-sm btn-primary">view</a>
                </div>
            </div>
        </div>            

        <div class="col-md-4">
            <div class="profile-div">
                <span>Voter Id</span>
                <div>
                    <label>@{{client.voter_id_no}}</label>
                    <a ng-if="client.voter_id_card" href="{{url('/')}}/@{{client.voter_id_card}}" target="_blank"  class="btn btn-sm btn-primary">view</a>
                </div>
            </div>
        </div>
    </div>
    <u>Address</u>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-div">
                <span>Address</span>
                <label>@{{client.address}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Pin code</span>
                <label>@{{client.pin}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>State</span>
                <label>@{{client.state_name}}</label>
            </div>
        </div>            
        <div class="col-md-4">
            <div class="profile-div">
                <span>District</span>
                <label>@{{client.district_name}}</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Block</span>
                <label ng-if="client.block_name">@{{client.block_name}}</label>
                <label ng-if="client.block_name">NA</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Village</span>
                <label>@{{client.village_name}}</label>
            </div>
        </div>
    </div>

    <h3 ><u>Guarantor Data</u></h3>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="table-div pro-div">
                <div class="image">
                    <img ng-if="guarantorData.photo" src="{{url('/')}}/@{{guarantorData.photo}}" style="width:120px;height:120px;border-radius: 50%;margin-right: 24px;">
                    
                </div>
                <div class="info">
                    <h3 class="name">
                         @{{guarantorData.name}}
                    </h3>
                </div>
            </div>
            
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>Name</span>
                <div>
                    <label>@{{guarantorData.name}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>Email</span>
                <div>
                    <label>@{{guarantorData.email}}</label>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Mobile</span>
                <div>
                    <label>@{{guarantorData.mobile}}</label>
                </div>
            </div>
        </div>
    </div>
    <u>Documents</u>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-div">
                <span>Aadhaar No</span>
                <div>
                    <label>@{{guarantorData.aadhaar_no}}</label>
                    <a ng-if="guarantorData.aadhaar_card" href="{{url('/')}}/@{{guarantorData.aadhaar_card}}" class="btn btn-sm btn-primary" target="_blank">view</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profile-div">
                <span>Voter ID</span>
                <div>
                    <label>@{{guarantorData.voter_id_no}}</label>
                    <a ng-if="guarantorData.voter_id_card" href="{{url('/')}}/@{{guarantorData.voter_id_card}}" class="btn btn-sm btn-primary" target="_blank">view</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>PAN No</span>
                <div>
                    <label>@{{guarantorData.pan_no}}</label>
                    <a ng-if="guarantorData.pan_no" href="{{url('/')}}/@{{guarantorData.pan_no}}" class="btn btn-sm btn-primary" target="_blank">view</a>
                </div>
            </div>
        </div>
    </div>

    <u>Bank Details</u>
    <hr>

    <div class="row">
        <div class="col-md-4">
            <div class="profile-div">
                <span>Bank Name</span>
                <div>
                    <label>@{{guarantorData.bank_name }}</label>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>IFSC Code</span>
                <div>
                    <label>@{{guarantorData.ifsc_code}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="profile-div">
                <span>IFSC Code</span>
                <div>
                    <label>@{{guarantorData.ac_no}}</label>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection