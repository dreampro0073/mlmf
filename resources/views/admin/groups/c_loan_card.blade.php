@extends('admin.layout')


@section('header_scripts')
 
@endsection

@section('main')

<div class="main" ng-controller="loanCardCtrl" ng-init="group_id={{$group_id}};customer_id={{$customer_id}};getCLoanCard();">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Loan Card</h1>
        </div>
        <div class="col-md-6 text-right">
            <!-- <a href="javascript:;" class="btn btn-warning" ng-click="printCard()">Print</a> -->
            <a href="{{url('admin/groups/print-loan-card/'.$group_id.'/'.$customer_id)}}" class="btn btn-warning" >Print</a>
            <a href="{{url('admin/groups/view'.$group_id)}}" class="btn btn-primary">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4 p-4">
        <div class="loan-div" id="loan-card">
            <div class="row personal-bio">
                <div class="col-md-6">
                    <div class="bio">
                        <h4>
                            Group Name: @{{group.group_name}}
                        </h4>
                        <h5>
                            Customer's Details
                        </h5>
                        <h6>
                            ID: <span>@{{customer.unique_id}}</span>
                        </h6>
                        <h6>
                            Name: <span>@{{customer.name}}</span>
                        </h6>
                        <p>
                            Fathers/Husband Name : <span>@{{customer.father_husband_name}}</span>
                        </p>
                        <p>
                            Mobile : <span>@{{customer.mobile}}</span>
                        </p>
                        <p>
                            Guaranter Name : <span>@{{customer.guarantor_name}}</span>
                        </p>
                        <p>
                            Guaranter Mobile : <span>@{{customer.guarantor_mobile}}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="image">
                        <img src="{{url('/')}}/@{{customer.joint_photo}}" style="width:200px;height: 150px;object-fit: cover;">
                    </div>
                </div>

            </div>
            <hr>
            <div class="row personal-bio mb-4">
                <div class="col-md-6">
                    <div class="bio">
                       
                        <p>
                            Amount: <span>@{{group.principal_amount}}</span>
                        </p>
                        <p>
                            Borrower's Aim : <span>@{{customer.aim}}</span>
                        </p>
                        <p>
                            Date : <span>@{{group.start_date}}</span>
                        </p>

                        <p>
                            Time Period : <span>@{{group.no_of_emis}}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image">
                        
                    </div>
                </div>
                
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" width="100%" cellspacing="0" border="1">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No.</th>
                                <th>Date</th>
                                <th>Principal Repayment</th>
                                <th>Interest</th>
                                <th>Principal Payment</th>   
                                <th>EMI</th>
                                <th>Signature</th>   
                                <th>LIR No.</th>   
                                <th>Penality</th>   
                                <th>Remarks.</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in group.group_dates track by $index" class="@{{item.ad_cl}}">
                                <td>@{{$index+1}}</td>
                                <td>@{{item.emi_date}}</td>
                                <td>@{{item.principal_repayment}}</td>

                                <td>@{{item.interest_payment}}</td>

                                <td>@{{item.principal_payment}}</td> 
                                <td>@{{item.emi_amount}}</td>

                                <td></td>   
                                <td></td>  
                                <td>@{{item.penalty_amount}}</td>   
                                <td>@{{item.remark}}</td>   
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>@{{group.total_int_amount}}</td>
                                <td></td>
                                <td>@{{group.total_amount}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        
    </div>  
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection