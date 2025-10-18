<?php
    $clients_ids = [1,3];
    $user = Session::get('user');
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        
        <div class="sidebar-brand-text mx-3">
            <!-- <img  src="{{url('assets/img/logo.png')}}" style="height:60px;width:auto;"> -->
            <span>
                {{Auth::user()->name}}
            </span>
        </div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='dashboard' && $subsidebar == 'dashboard')?'active':''}}" href="{{url('admin/dashboard')}}">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @if(Auth::user()->client_id != 3)
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='clients' && $subsidebar == 'clients')?'active':''}}" href="{{url('admin/clients')}}">
            <i class="fa fa-users" aria-hidden="true"></i>
            <span>Clients</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{($sidebar =='plans' && $subsidebar == 'plans')?'active':''}}" href="{{url('admin/plans')}}">
            <i class="fa fa-file-text" aria-hidden="true"></i>
            <span>Plans</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{($sidebar =='groups' && $subsidebar == 'groups')?'active':''}}" href="{{url('admin/groups')}}">
            <i class="fa fa-users" aria-hidden="true"></i>

            <span>Groups</span>
        </a>
    </li>
    @endif
    @if(Auth::user()->privilege == 1)
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='blocks' && $subsidebar == 'blocks')?'active':''}}" href="{{url('admin/blocks')}}">
            <i class="fa fa-users" aria-hidden="true"></i>

            <span>Blocks</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='villages' && $subsidebar == 'villages')?'active':''}}" href="{{url('admin/villages')}}">
            <i class="fa fa-users" aria-hidden="true"></i>

            <span>Villages</span>
        </a>
    </li>
    @endif
    @if(in_array(Auth::id(),$clients_ids))
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='expenses' && $subsidebar == 'expenses')?'active':''}}" href="{{url('admin/expenses')}}">
            <i class="fa fa-money" aria-hidden="true"></i>

            <span>Expenses</span>
        </a>
    </li>    

    <li class="nav-item">
        <a class="nav-link {{($sidebar =='incomes' && $subsidebar == 'incomes')?'active':''}}" href="{{url('admin/income')}}">
            <i class="fa fa-money" aria-hidden="true"></i>

            <span>Income</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{($sidebar =='banking' && $subsidebar == 'banking')?'active':''}}" href="{{url('admin/banking')}}">
            <i class="fa fa-money" aria-hidden="true"></i>
            <span>Banking</span>
        </a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" href="{{url('logout')}}">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            <span>Logout</span>
        </a>
    </li>
  
    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle">
            
        </button>
    </div>

</ul>
