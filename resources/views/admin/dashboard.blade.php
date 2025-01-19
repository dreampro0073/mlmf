@extends('admin.layout')

@section('main')

<div class="main">
    <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>	
   	<div class="row">
        <div class="col-md-3">
            <a class="no-dec" href="{{url('/admin/groups/today/collection')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Today Target</p>
                    <p>{{$today_target}}
                        
                    </p>
                    <div class="icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3">
            <a class="no-dec" href="{{url('/admin/groups/today/collection/-1')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Today Collection</p>
                    <p>{{$today_collection}}
                        
                    </p>
                    <div class="icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </a>    
        </div>
        
        <div class="col-md-3">
            <a class="no-dec" href="{{url('admin/groups')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Active Groups</p>
                    <p>
                        {{ $groups }}
                    </p>
                    <div class="icon">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                </div>
            </a>    
        </div>
        
        <div class="col-md-3">
            <a class="no-dec" href="{{url('admin/plans')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Active Plans</p>
                    <p>
                        {{$plans}}
                    </p>
                    <div class="icon">
                        <i class="fa fa-file-text" aria-hidden="true"></i>
                    </div>
                </div>
            </a>    
        </div>
        
        <div class="col-md-3">
            <a class="no-dec" href="{{url('admin/clients')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Our Clients</p>
                    <p>
                        {{$clients}}
                    </p>
                    <div class="icon">
                        <i class="fa fa-users" aria-hidden="true"></i>

                    </div>
                </div>
            </a>    
        </div>

   	</div>
    <div class="main" ng-controller="dashCtrl" ng-init="dashInit()">
        <div ng-if="pending_list.length > 0">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Client Name</th>
                        <th>Group Name</th>
                        <th>EMI Date</th>
                        <th>EMI Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in pending_list" style="color: red;">
                        <td>@{{ $index+1}}</td>
                        <td><a href="{{url('admin/clients/details/')}}/@{{item.enc_id}}" target="_blank">@{{item.customer_name}} /
                            <span style="display:font-size: 12px;">@{{item.mobile}}</span> </a>
                        </td>
                        <td><a href="{{url('admin/groups/view/')}}/@{{item.group_id}}" target="_blank">@{{item.group_name}}</a></td>
                        <td>@{{item.emi_date|date:'dd-MM-yyyy'}}</td>
                        <td>@{{item.emi_amount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection
