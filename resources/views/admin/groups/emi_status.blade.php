@extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main" ng-controller="groupsCtrl" ng-init="group_id={{$group_id}};groupEMIInit()">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                @{{group_details.group_name}} - @{{group_details.village_name}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <b>Date - <?php echo date('d-m-Y') ?></b>
        </div>
    </div>   

    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Customer</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="item in customers_emis">
                <td>
                    @{{ $index+1 }}
                </td>
                <td>
                    <a href="{{url('admin/clients/details/')}}/@{{item.id}}" target="_blank">
                        @{{item.name}}
                    </a>
                </td>
                <td>@{{item.emi_amount}}</td>
                <td>
                </td>
            </tr>
        </tbody>
    </table>

</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection