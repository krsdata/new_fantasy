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
                                        <span class="caption-subject font-red sbold uppercase">{{ $heading }}</span>
                                    </div>
                                    <div class="col-md-12 pull-right">
                                        <div class=" pull-right">
                                            <div   class="input-group"> 
                                                <a href="{{ route('match')}}">
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Back </button> 
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
                                <div class="portlet-body"> 
                                    <table class="table table-striped table-hover table-bordered" id="contact">  
                                        <tbody>
                                            @foreach($match as $key => $result)
                                            <tr>
                                                @if($key=='created_at') 
                                                <th>  Created Date </th>
                                                <td>  
                                                     {!! Carbon\Carbon::parse($result)->format('m-d-Y'); !!}

                                                </td> 
                                                 @else
                                                <th>  {{ str_replace('_',' ',ucfirst($key)) }} </th>
                                                <td> {{ str_replace('_',' ',ucfirst($result)) }} </td>
                                                 @endif  
                                            </tr>
                                           @endforeach 
                                        </tbody>
                                    </table>  

                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr> <td colspan="9">
                                               <b> <center> Contest List </center> </b>
                                            </td> </tr>
                                        </thead>
                                        <thead>
                                            <tr> 
                                                 <th> Ctrate contest Id </th>
                                                <th> Match Id </th>
                                                <th> contest_type</th> 
                                                <th> total_winning_prize </th> 
                                                <th> entry_fees </th> 
                                                <th> total_spots</th> 
                                                <th> filled_spot </th> 
                                                 <th> first_prize </th>  
                                                 <th> cancellation </th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($conetst as $key => $result)
                                            <tr>
                                                 <td> {{$result->id}} </td>
                                                 <td> {{$result->match_id}} </td>
                                                 <td> {{$result->contest_type}} </td>
                                                <td> {{$result->total_winning_prize}} </td>

                                                <td> {{$result->entry_fees}} </td>
                                                <td> {{$result->total_spots}} </td>
                                                <td> {{$result->filled_spot}} </td>
                                                <td> {{$result->first_prize}} </td>
                                                <td> {{$result->cancellation}} </td>
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>

                                      <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr> <td colspan="9">
                                               <b> <center> Player List </center> </b>
                                            </td> </tr>
                                        </thead>
                                        <thead>
                                            <tr>  <th> Sno.</th>
                                                 <th> Match Id </th>
                                                <th> PID</th> 
                                                <th> Player Team ID </th> 
                                                <th> Player Name </th> 
                                                <th> Country</th> 
                                                <th> Playing role </th> 
                                                 <th> Nationality </th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($player as $key => $result)
                                            <tr>
                                                <td> {{++$key}} </td>
                                                  <td> {{$result->match_id}} </td>
                                                 <td> {{$result->pid}} </td>
                                                <td> {{$result->team_id}} </td>

                                                <td> {{$result->title}} </td>
                                                <td> {{$result->country}} </td>
                                                <td> {{$result->playing_role}} </td>
                                                <td> {{$result->nationality}} </td> 
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
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