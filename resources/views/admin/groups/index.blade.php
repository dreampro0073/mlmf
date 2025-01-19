@extends('admin.layout')


@section('header_scripts')
 
@endsection

@section('main')

<div class="main" ng-controller="groupsCtrl">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Groups</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups/add')}}" class="btn btn-primary">Add</a>
            <a href="{{url('old-groups/add')}}" class="btn btn-primary">Add Old Group</a>
        </div>
    </div>    
    <div class="card shadow mb-4"> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Name</th>
                            <th>Plan</th>
                            <th>Block</th>
                            <th>Village</th>
                            <th>Pin Code</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1;?>
                        @foreach($groups as $item)
                        <tr>
                            <td>{{ $index++}}</td>
                            <td>{{ $item->group_name}}</td>
                            <td>{{ $item->plan_name}}</td>
                            <td>{{ $item->block_name}}</td>
                            <td>{{ $item->village_name}}</td>
                            <td>{{ $item->pin_code }}</td>
                            <td>
                                <a href="{{url('admin/groups/view/'.$item->id)}}" class="btn btn-info btn-sm">View</a>
                                @if($item->active == 0)
                                <a href="{{url('admin/groups/add/'.$item->id)}}" class="btn btn-primary btn-sm">Edit</a> 
                                @endif


                                @if($item->active == 0)

                                <a href="{{url('admin/groups/delete/'.$item->id)}}" onclick="return confirm('Are you sure to Delete?');" class="btn btn-danger btn-sm">Delete</a>
                                
                                @endif

                                @if($item->active == 0)
                                    <button type="button" ng-disabled="active_loading" class="btn btn-sm btn-primary"  ng-click="actvateGroup({{$item->id}})">
                                        <span ng-if="!active_loading">Activate</span>
                                        <span ng-if="active_loading" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                @endif

                               
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> 


</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection