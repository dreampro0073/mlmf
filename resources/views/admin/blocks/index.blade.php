<?php $version=env('JS_VERSION'); ?>
@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

<div class="main">

    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Blocks</h1>
        </div>
         <div class="col-md-6 text-right">
            <a href="{{url('admin/blocks/add')}}" class="btn btn-primary">Add</a>
        </div>
        
    </div>    
    <div class="card shadow mb-4"> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Block Name</th>
                            <th>District</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1;?>
                        @foreach($blocks as $item)
                        <tr>
                            <td>{{ $index++}}</td>
                            <td>{{$item->block_name}}</td>
                            <td>{{$item->city_name}}</td>
                            <td>
                                <a href="{{url('admin/blocks/add/'.$item->id)}}" class="btn btn-sm btn-warning">Edit</a>
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
    <script type="text/javascript" src="{{url('assets/scripts/core/block_ctrl.js?v='.$version)}}" ></script>
@endsection