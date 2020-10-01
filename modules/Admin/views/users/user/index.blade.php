@extends('packages::layouts.master')
@section('title', 'Dashboard')
@section('header')
<h1>Dashboard</h1>
@stop
@section('content') 
@include('packages::partials.navigation')
<!-- Left side column. contains the logo and sidebar -->
@include('packages::partials.sidebar') 

<!-- END SIDEBAR -->
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
    <!-- BEGIN PAGE HEAD-->
    
    <!-- END PAGE HEAD-->
    <!-- BEGIN PAGE BREADCRUMB -->
     @include('packages::partials.breadcrumb')
    <!-- END PAGE BREADCRUMB -->
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">{{$heading ?? ''}}</span>
                    </div>
                     
                <div class="col-md-2 pull-right">
                                            <div style="width: 150px;" class="input-group"> 
                                                <a href="#" data-toggle="modal" data-target="#notification"> 
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Send notification</button> 
                                                </a>
                                            </div>
                                        </div>

                </div>
                <div class="portlet-body table-responsive">
                    <div class="table-toolbar">
                        <div class="row">
                            <form action="{{route('user')}}" method="get" id="filter_data">
                            <div class="col-md-2">
                                <select name="status" class="form-control" onChange="SortByStatus('filter_data')">
                                    <option value="">Search by Status</option>
                                    <option value="active" @if($status==='active') selected  @endif>Active</option>
                                    <option value="inActive" @if($status==='inActive') selected  @endif>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="role_type" class="form-control" onChange="SortByStatus('filter_data')">
                                    <option value="">Search by Role</option>
                                    @if($roles)
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if($role_type==$role->id) selected @endif >{{$role->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="search by Name/Email" type="text" name="search" id="search" class="form-control" >
                            </div>

                            <div class="col-md-2">
                                <input value="{{ (isset($_REQUEST['mobile_number']))?$_REQUEST['mobile_number']:''}}" placeholder="search by mobile number" type="text" name="mobile_number" id="search" class="form-control" >
                            </div>
                            <div class="col-md-2">
                                <input type="submit" value="Search" class="btn btn-primary form-control">
                            </div>
                           
                        </form>
                         
                       <div class="col-md-2 pull-right">
                            <div style="width: 150px;" class="input-group"> 
                                <a href="{{ route('user.create')}}">
                                    <button class="btn  btn-primary"><i class="fa fa-user-plus"></i> Add User</button> 
                                </a>
                            </div>
                        </div> 
                        </div>
                    </div>

                     @if(Session::has('flash_alert_notice'))
                         <div class="alert alert-success alert-dismissable" style="margin:10px">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                          <i class="icon fa fa-check"></i>  
                         {{ Session::get('flash_alert_notice') }} 
                         </div>
                    @endif
                    @if($users->count()==0)
                   
                     <span class="caption-subject font-red sbold uppercase"> Record not found!</span>
                    @else 
                    <table class="table table-striped table-hover table-bordered" id="">
                        <thead>
                            <tr>
                                 <th> Sno. </th>
                                 <th>User Details</th>
                                 <th>Referral Code</th>
                                 <th>Team Name</th>
                                <th> Full Name </th>
                                <th> Email </th>
                                 <th> Account Balance </th>
                                <th> Phone </th>
                                <th> {{($heading=='Admin Users')?'User Type':''}} </th>
                                <th>Signup Date</th>
                                <th>Status</th>
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>

                    
                        @foreach($users as $key => $result)
                            <tr>
                                 <td> {{ (($users->currentpage()-1)*15)+(++$key) }}</td>
                                 <td>

                              <table class="table table-striped table-hover table-bordered">
                                <tr>
                                  <td>User Id</td>
                                  <td>{{$result->id}}</td>    
                                </tr>
                                <tr>
                                  <td>UserName</td>
                                  <td>{{$result->user_name}}</td>    
                                </tr>
                                <tr>
                                  <td>Referral</td>
                                  <td>{{$result->referal_code}}</td>
                                </tr>
                                <tr>
                                  <td>Used by</td>
                                  <td>{{$result->ref_count}}</td>
                                </tr>
                                <tr>
                                  <td>User deposit</td>
                                  <td>{{round($result->ref_deposit??0,2)}}</td>
                                </tr>
                                <tr>  
                                  <td>Referral Deposit</td>
                                  <td>{{round($result->reference_deposit??0,2)}}</td>
                                </tr>
                                <tr>  
                                  <td>Affiliate User</td>
                                  <td>{{($result->affiliate_user?'Yes':'No')}}</td>
                                </tr>

                              </table>
                                 </td>
                                 <td> {{$result->reference_code}}
                                 </td>
                                 <td> {{$result->team_name}}</td>
                                <td> {{$result->name}} </td>
                                <td> {{$result->email}} </td>
                                 <td>  

                                    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#account_{{$result->id}}">
 {{round($result->balance,2)}} INR
</button>

<!-- Modal -->
<div class="modal fade" id="account_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Account Summary</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table  class="table table-striped table-hover table-bordered"> 
        @foreach($result->amount as $key => $val)
          <tr>
              <td>{{ucfirst($key)}}</td>
              <td>{{round($val,2)}}</td>
          </tr>
        @endforeach
        <tr>
              <td>Available Balance</td>
              <td>{{round($result->balance,2)}} INR</td>
          </tr>
        
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="{{url('admin/paymentsHistory?search='.$result->id)}}">
        <button type="button" class="btn btn-primary">
            View All Transaction
        </button>
      </div>
    </div>
  </div>
</div>

                                 </td>
                                <td> {{$result->mobile_number}} </td>
                                <td class="center"> 
                               
                                    @if($result->role_type==3)
                                    <a href="{{ route('user.edit',$result->id)}}?role_type={{$result->role_type}}">
                                        View Details
                                        <i class="glyphicon glyphicon-eye-open" title="edit"></i> 

                                    </a>
                                    @else
                                      {{ ($result->role_type==1)?'admin':($result->role_type==2)?'Sales':($result->role_type==4)?'Support':'Admin'}}
                                    @endif
                                </td>
                                <td>
                                    {!! Carbon\Carbon::parse($result->created_at)->format('d-m-Y'); !!}
                                </td>
                                <td>
                                    <span class="label label-{{ ($result->status==1)?'success':'warning'}} status" id="{{$result->id}}"  data="{{$result->status}}"  onclick="changeStatus({{$result->id}},'user')" >
                                            {{ ($result->status==1)?'Active':'Inactive'}}
                                        </span>
                                </td>
                                <td> 
                                    <a href="{{ route('user.edit',$result->id)}}?role_type={{$result->role_type}}">
                                            <i class="fa fa-fw fa-pencil-square-o" title="edit"></i> 
                                        </a>

                                        {!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('user.destroy', $result->id))) !!}
                                        <button class='delbtn btn btn-danger btn-xs' type="submit" name="remove_levels" value="delete" id="{{$result->id}}"><i class="fa fa-fw fa-trash" title="Delete"></i></button>
                                        
                                         {!! Form::close() !!}

                                    </td>
                               
                            </tr>
                           @endforeach
                         @endif   
                        </tbody>
                    </table>
                    Showing {{($users->currentpage()-1)*$users->perpage()+1}} to {{$users->currentpage()*$users->perpage()}}
                    of  {{$users->total()}} entries
                     <div class="center" align="center">  {!! $users->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}</div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>
<!-- END CONTENT BODY -->
</div>


<!-- END QUICK SIDEBAR -->
</div>

<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notify User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <div class="modal-body">

{!! Form::model($users, ['route' => ['user.store'],'class'=>'form-horizontal user-form','id'=>'user-form','enctype'=>'multipart/form-data']) !!}

<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
        <div class="form-group {{ $errors->first('message_type', ' has-error') }}">
            <label class="control-label col-md-3">Message Type <span class="required"> * </span></label>
            <div class="col-md-7"> 
                <select class="form-control" name="message_type"> 
                        <option value="notify">Notify</option>
                </select>
                
                <span class="help-block">{{ $errors->first('message_type', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('title', ' has-error') }}">
            <label class="control-label col-md-3">Email <span class="required"> * </span></label>
            <div class="col-md-7"> 
                {!! Form::email('email',null, ['class' => 'form-control','data-required'=>1])  !!} 
                <input type="hidden" name="notification" value="notification">
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div>
 
        <div class="form-group {{ $errors->first('title', ' has-error') }}">
            <label class="control-label col-md-3">Title <span class="required"> * </span></label>
            <div class="col-md-7"> 
                {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!} 
                
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div> 

          <div class="form-group {{ $errors->first('message', ' has-error') }}">
            <label class="control-label col-md-3">Message<span class="required"> </span></label>
            <div class="col-md-7"> 
                {!! Form::textarea('message',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
                
                <span class="help-block">{{ $errors->first('message', ':message') }}</span>
            </div>
        </div>

    
        
    <div class="form-actions">
        <div class="row" style="padding-right:12px">
            <div class="col-md-10">
              {!! Form::submit(' Send Notification ', ['class'=>'btn  btn-success pull-right','id'=>'saveBtn']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}   


   </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>


<script type="text/javascript">

function SortByStatus(filter_data) {
$('#filter_data').submit();
}
</script>

@stop