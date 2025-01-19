 @extends('admin.layout')

@section('header_scripts')
  
@endsection

@section('main')
<div class="main">
    <div class="mb-4 row mt-3">
        <div class="col-md-6">
            <div class="table-div pro-div">
                <div class="image">
                    <img ng-if="client.customer_photo" src="{{url('/'.$client->customer_photo)}}" style="width:120px;height:120px;border-radius: 50%;margin-right: 24px;">
                    
                </div>
                <div class="info">
                    <h3 class="name">
                        {{$client->name}}
                    </h3>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    <div>
        @foreach($groups as $group)
            <div class="row">
                <div class="col-md-6">
                    <h3>{{ $group->group_name }}</h3>
                </div>
                <div class="col-md-6 text-right">
                    @if($group->closed == 0)
                    <a onclick="return confirm('Are you sure ?');" href="{{url('admin/groups/close-group/'.$group->id.'/'.$enc_id)}}" class="btn btn-danger btn-sm">Close Group</a>
                    @else
                        <span style="" class="btn btn-success btn-sm">Closed</span>
                    @endif
                </div>
            </div>

           <table class="table table-bordered">
               <thead>
                   <tr>
                       <th>EMI Date</th>
                       <th>EMI Amount</th>
                       <th>Collection Status</th>
                   </tr>
               </thead>
               @foreach($group->group_emi_dates as $detail)
               <tbody>
                   <tr>
                        <td>{{$detail->emi_date}}</td>
                        <td>{{$detail->emi_amount}}</td>
                        <td>
                            {{$detail->emi_status}}
                        </td>
                   </tr>
               </tbody>
                @endforeach

           </table> 
        @endforeach
    </div>
</div>

@endsection

@section('footer_scripts')
    
@endsection