@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

<div class="main" ng-controller="clientsCtrl">

    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Clients</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/clients/add')}}" class="btn btn-primary">Add</a>
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
                            <th>Father / Husband Name</th>
                            <th>Mobile</th>
                            <th>Aadhaar</th>
                            <th>Status</th>
                            <th>Address</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1;?>
                        @foreach($clients as $item)
                        <tr>
                            <td>{{ $index++}}</td>
                            <td>{{ $item->name}}</td>
                            <td>{{ $item->father_husband_name}}</td>
                            <td>{{ $item->mobile}}</td>
                            <td>{{ $item->aadhaar_no}}</td>
                            <td>{{ $item->kyc_status}}</td>
                            <td>{{ $item->address}}</td>
                            <td>
                                <a href="{{url('admin/clients/details/'.$item->enc_id)}}" class="btn btn-info btn-sm">View</a> 
                                <a href="{{url('admin/clients/add/'.$item->enc_id)}}" class="btn btn-primary btn-sm">Edit</a> 
                                <a href="{{url('admin/clients/history/'.$item->enc_id)}}" class="btn btn-warning btn-sm">History</a> 
                               <a href="{{url('admin/clients/delete/'.$item->enc_id)}}" onclick="return confirm('Are you sure to Delete?');" class="btn btn-danger btn-sm">Delete</a> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(sizeOf($clients) == 0)
                    <div class="alert alert-danger">Data Not Found!</div>
                @endif
            </div>
        </div>
    </div>
    
    
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.3"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection