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
        
       <!--  <div class="col-md-3">
            <a class="no-dec" href="{{url('/')}}">
                <div class="card p-3 shadow mb-4 ">
                    <p class="fs-30">Pending</p>
                    <p>
                        
                    </p>
                    <div class="icon">
                        <i class="fas fa-university"></i>
                    </div>
                </div>
            </a>    
        </div> -->
        
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
</div>
@endsection