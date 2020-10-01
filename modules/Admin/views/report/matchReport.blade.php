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
                                        <span class="caption-subject font-red sbold uppercase"> Matches Reports({{$total_match??0}}) </span>
                                    </div>
                                     <div class="col-md-12 pull-right">
                                         

                                       

                                      <button type="button" class="btn pull-right btn-success" data-toggle="modal" data-target="#getMatchReport" data-whatever="@" style="margin-right: 10px">Download Report</button>
                                         
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
                                            <form action="{{route('getMatchReport')}}" method="get" id="filter_data">
                                            
                                            <div class="col-md-2">
                                              <input id="" class="form-control   valid s_date" data-required="1" size="16" data-date-format="yyyy-mm-dd"   name="start_date" type="text" aria-invalid="false" placeholder="Start Date" value="{{ (isset($_REQUEST['start_date']))?$_REQUEST['start_date']:''}}"> 

                                            </div>

                                             <div class="col-md-2">
                                              <input id="" class="form-control end_date e_date" data-required="1" size="16" data-date-format="dd-mm-yyyy" name="end_date" type="text" placeholder="End Date" value="{{ (isset($_REQUEST['end_date']))?$_REQUEST['end_date']:''}}">

                                            </div>

                                            <div class="col-md-2">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by id or name" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                              <div class="col-md-2">
                                                <select class="form-control" name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="1" @if(isset($_REQUEST['status']) && $_REQUEST['status']==1) selected @endif>Upcoming</option>
                                                     <option value="2" <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==2) { echo "selected"; }  ?>> Completed</option>
                                                      <option value="3" @if(isset($_REQUEST['status']) && $_REQUEST['status']==3) selected @endif>Live</option>
                                                      <option value="4" @if(isset($_REQUEST['status']) && $_REQUEST['status']==4) selected @endif>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('getMatchReport') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr>
                                                <th> Sno.</th>
                                                <th> Match Id</th>
                                                <th> Match Name </th>  
                                                <th> Total Contest</th>
                                                <th> Total Contest Joined</th>
                                                <th>  User Played</th>
                                                <th>  Prize Given</th>
                                                <th>Amount Recieved</th>
                                                <th> Status</th> 
                                                <th> Date </th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($match as $key => $result)
                                            <tr>
                                              <td>
                                                {{ $key+1 }}
                                            </td>
                                                <td> {{$result->match_id}} </td>
                                                 <td> {{$result->short_title}} </td>
                                                 <td> 
                                                  {{$result->total_contest}}
                                                </td>
                                                 <td> 
                                                  {{$result->join_contest}}
                                                </td>
                                                <td> 
                                                  {{$result->total_user_played}}
                                                </td>
                                                <td> 
                                                  {{$result->
                                                total_prize_distributed}}
                                                </td>
                                                <th>{{$result->total_amt_rcv}}</th>
                                                
                                               
                                      <td> {{$result->status_str}} </td>
                                      <td>{{ date('d,M Y, h:i A',$result->timestamp_end)}}
                                      </td>
                                         
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>
                            Showing {{($match->currentpage()-1)*$match->perpage()+1}} to {{$match->currentpage()*$match->perpage()}}
                                    of  {{$match->total()}} entries <br><br>

                             <span id="error_msg"></span>
                               
                        <div class="center" align="center">  {!! $match->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}
                         </div>
                             
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


<!-- start match -->
<div class="modal fade" id="changeMatchStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Match Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
           
          <input type="hidden" name="change_status" value="change_status">
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Match Id:</label>
            <input type="text" class="form-control" id="match_id"  name="match_id" required="" >
          </div>
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Match Status:</label>
             <select class="form-control" name="status" required="">
                <option value="">Select Status</option>
                <option value="1">Upcoming</option>
                <option value="2">Completed</option>
                <option value="3">Live</option>
                <option value="4">Cancelled</option>
             </select> 
          </div>
         <!--  <div class="form-group">
            <label for="message-text" class="col-form-label">Match Id:</label>
            <textarea class="form-control" id="message-text" ></textarea>
          </div> -->
           <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"> Save </button>
      </div>
        </form>
      </div>
     
    </div>
  </div>
</div>
<!-- End status -->

<div class="modal fade" id="changeDate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Match Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" name="change_date" value="change_date">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Start Date:</label>
            <input type="text" class="form-control form_datetime_start form_datetime" id="start_date" value="{{date('Y-m-d H:i')}}" readonly name="date_start">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label" >End Date:</label>
            <input type="text" class="form-control form_datetime_end form_datetime" id="end_date" value="{{date('Y-m-d H:i')}}" readonly name="date_end">
          </div>
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Match Id:</label>
            <input type="text" class="form-control" id="match_id"  name="match_id" >
          </div>
            
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"> Save </button>
        </div>

        </form>
      </div>
     
    </div>
  </div>
</div>

<div class="modal fade" id="popMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Email sent successfully</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="popMsg2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Prize distributed successfully!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="saveMatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Save Match information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <p> Please wait while match information is getting saved </p>
      </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>




<div class="modal fade" id="getMatchReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Download Reports</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{url('admin/downloadMatchReport')}}">
          <input type="hidden" name="change_date" value="change_date">
          <div class="form-group">

            <label for="recipient-name" class="col-form-label">Start Date:</label>
            <input type="text" class="form-control s_date" id=""   readonly name="date_start" data-date-format="yyyy-mm-dd"> 


          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label" >End Date:</label>
            <input type="text" class="form-control e_date" id=""  readonly name="date_end">
          </div> 
          
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Montly:</label>
             <select class="form-control" name="month">
                <option value="">Select Month</option>
                <option value="1">JAN</option>
                <option value="3">FEB</option>
                <option value="3">MARCH</option>
                <option value="4">APRIL</option>
                <option value="5">MAY</option>
                <option value="6">JUNE</option>
                <option value="7">JULY</option>
                <option value="8">AUG</option>
                <option value="9">SEP</option>
                <option value="10">OCT</option>
                <option value="11">NOV</option>
                <option value="12">DEC</option> 
                
             </select> 
          </div>

            
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"> Save </button>
        </div>

        </form>
      </div>
     
    </div>
  </div>
</div>

@stop