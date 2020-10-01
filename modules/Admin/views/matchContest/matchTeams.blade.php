
@extends('packages::layouts.master')
  @section('title', 'Dashboard')
    @section('header')
    <h1>Dashboard</h1>
    @stop
    @section('content') 
      @include('packages::partials.navigation')
      <!-- Left side column. contains the logo and sidebar -->
      @include('packages::partials.sidebar')
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
                                <span class="caption-subject font-red sbold uppercase">{{ $page_title }}</span>
                            </div>
                        </div>
                            @if(Session::has('flash_alert_notice'))
                                 <div class="alert alert-success alert-dismissable" style="margin:10px">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  <i class="icon fa fa-check"></i>  
                                 {{ Session::get('flash_alert_notice') }} 
                                 </div>
                            @endif
                        <div class="portlet-body table-responsive">
                            <div class="table-toolbar">
                                <div class="row">
                                    <form action="{{route('matchTeams')}}" method="get" id="filter_data">
                                     
                                    <div class="col-md-3">
                                        <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  name,email" type="text" name="search" id="search" class="form-control" >
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" value="Search" class="btn btn-primary form-control">
                                    </div>
                                   
                                </form>
                                 <div class="col-md-2">
                                     <a href="{{ route('matchTeams') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                </div>
                               
                                </div>
                            </div>
                             
                            <table class="table table-striped table-hover table-bordered" id="">
                                <thead>
                                    <tr>
                                        <th>Sno.</th>
                                   @foreach($tables as $col_name)
                                        <th> {{  \Str::replaceFirst('_'," ",ucfirst($col_name)) }}</th> 
                                    @endforeach
                                    <th>Action</th>
                                    </tr>
                                </thead>

                                @if($matchTeams->count()==0)
                                <div class="alert alert-danger"><h2> Team not created yet! <h2> </div>
                                @endif
    <tbody>
         @foreach($matchTeams as $key => $result)
        <tr>
            <td> {{ (($matchTeams->currentpage()-1)*15)+(++$key) }}</td>
            @foreach($tables as $col_name)

                   <td>  {!!$result->$col_name!!} </td>
            @endforeach
                
            <td> 
                

                <a href="#"  data-toggle="modal" data-target="#viewTeams_{{$result->id}}">
                    <button class="btn btn-success btn-xs">
                       View Teams
                    <i class="fa fa-fw fa-eye" title="edit"></i> 
                    </button>
                </a>


<div class="modal fade" id="viewTeams_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg  " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Team List</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <form action="#"> 
      <div class="modal-body">

         <table class="table table-striped table-hover table-bordered" id="contact">
          <thead>
              <tr>
                  <th>Sno.</th>
                  <th> Player Name</th> 
                  <th> Role </th> 
                  <th> Captain </th>  
                  <th> Vice Captain </th>
                  <th> Trump </th>
              </tr>

          </thead>
          <tbody>
            @foreach($result->teams as $key => $team)
            <tr>
              <td>{{$key+1}} </td>
              <td>{{$team->title}}</td>
              <td>{{$team->playing_role}}</td>

              <td>@if($result->captain==$team->pid)
                    Yes
                    
                  @endif 
              </td>
              <td>@if($result->vice_captain==$team->pid)
                    Yes
                    
                  @endif  
              </td>
              <td>@if($result->trump==$team->pid)
                    Yes
                     
                  @endif  
              </td>
            </tr>
            @endforeach
 
          </tbody>
      </table>  

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button><!-- 
            <button type="submit" class="btn btn-success"> Cancel Selected Contest </button> -->
        </div>
      </div>
    </form>
</div>
</div>
</div>

                
                 {!! Form::close() !!}

            </td>
           
        </tr>
       @endforeach
        
    </tbody>
</table>
<span>
        Showing {{($matchTeams->currentpage()-1)*$matchTeams->perpage()+1}} to {{$matchTeams->currentpage()*$matchTeams->perpage()}}
                            of  {{$matchTeams->total()}} entries </span>
                            
 <div class="center" align="center">  {!! $matchTeams->appends(['search' => isset($_GET['search'])?$_GET['search']:'','contest_id' => isset($_GET['contest_id'])?$_GET['contest_id']:''])->render() !!}</div>
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
   
@stop