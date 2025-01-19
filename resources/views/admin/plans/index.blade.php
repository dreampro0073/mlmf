@extends('admin.layout')


@section('header_scripts')
 
@endsection

@section('main')

<div class="main" ng-controller="plansCtrl">

    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Plans</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/plans/add')}}" class="btn btn-primary">Add</a>
        </div>
    </div>    
    <div class="card shadow mb-4"> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Plan name</th>
                            <th>Principal Amount</th>
                            <th>Rate</th>
                            <th>Time Line (in day's)</th>   
                            <th>Total EMI's</th>
                            <th>EMI's Type</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1;?>
                        @foreach($plans as $item)
                        <tr>
                            <td>{{ $index++}}</td>
                            <td>{{ $item->plan_name}}</td>
                            <td>{{ $item->principal_amount}}</td>
                            <td>{{ $item->interest_rate}}</td> 
                            <td>{{ $item->time_line}}</td>
                            <td>{{ $item->no_of_emis}}</td>
                            <td>{{ $item->type_name}}</td>
                            <td>
                                <a ng-click="viewPlan({{$item->id}})" class="btn btn-primary btn-sm">View</a> 
                                @if($item->editable)
                                <a href="{{url('admin/plans/add/'.$item->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{url('admin/plans/delete/'.$item->id)}}" onclick="return confirm('Are you sure to Delete?');" class="btn btn-danger btn-sm">Delete</a> 
                                @endif 

                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="planModal" class="modal custom-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Plan : @{{plan.plan_name}}
                    <button type="btn" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>Plan Name</span>
                                <label>@{{plan.plan_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>Principal Amount</span>
                                <label>@{{plan.principal_amount}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>Time Line (IN Days)</span>
                                <label>@{{plan.time_line}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>Interest Rate (%)</span>
                                <label>@{{plan.interest_rate}} </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>EMI Type</span>
                                <label>@{{plan.type_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>Number Of EMIs</span>
                                <label>@{{plan.no_of_emis}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-div">
                                <span>EMI Amount</span>
                                <label>@{{plan.emi_amount}}</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/plan_ctrl.js?v='.$version)}}" ></script>

    
@endsection