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
                                        <span class="caption-subject font-red sbold uppercase">Old Matches({{$total_match??0}}) </span>
                                    </div>
                                     <div class="col-md-12 pull-right">
                                         

                                       

                                      <button type="button" class="btn pull-right btn-success" data-toggle="modal" data-target="#getOldMatch" data-whatever="@" style="margin-right: 10px">Get Old Match from Api</button>
                                         
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
                                            <form action="{{route('oldMatch')}}" method="get" id="filter_data">
                                            
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
                                             <a href="{{ route('oldMatch') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr>
                                                 <th>Sno.</th>
                                                <th> Match Id </th>
                                                <th> Match Between </th>  
                                                <th>  </th>  
                                                <th>Result</th>
                                                <th> Status</th> 
                                                <th> Date </th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($match as $key => $result)
                                            <tr>
                                              <td>
                                               <div class="mt-checkbox-list">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input type="checkbox" name="checkAll" id="chk_{{$result->match_id}}" value="{{$result->match_id}}">  
                                                    <span></span>
                                                </label>
                                                </div>
                                            </td>
                                                <td> {{$result->match_id}} </td>
                                                 <td> {{$result->title}} </td>
                                                 <td> <a class="btn btn-success" href="{{url('api/v2/saveMatchDataByMatchId/'.$result->match_id)}}"  data-target="#saveMatch" data-toggle="modal" >
                                                  Save This Match
                                                 </a> 
                                                </td>
                                                <td> <a class="btn btn-success" href="{{url('api/v2/saveMatchDataByMatchId/'.$result->match_id.'?Playing11=true')}}"  data-target="#saveMatch" data-toggle="modal" >
                                                  Enable Playing 11
                                                 </a> 
                                                </td>
                                                 
                                               
                                      <td> {{$result->status_note}} </td>
                                      <td> {{$result->status_str}} </td>
                                      <td>{!!\Carbon\Carbon::parse($result->date_start)      ->format('d,M Y') !!}
                                      </td>
                                         
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>
                            Showing {{($match->currentpage()-1)*$match->perpage()+1}} to {{$match->currentpage()*$match->perpage()}}
                                    of  {{$match->total()}} entries <br><br>

                             <span id="error_msg"></span>
                              <button class="btn btn-danger" onclick="SaveAllMatch('{{url('admin')}}','pages')">Save selected Match</button>
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




<div class="modal fade" id="getOldMatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Get Old Matches</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{url('admin/saveMatchFromApi')}}">
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
            <label for="recipient-name" class="col-form-label">Match Status:</label>
             <select class="form-control" name="status" required="">
                <option value="">Select Status</option>
                <option value="1">Upcoming</option>
                <option value="2">Completed</option>
                <option value="3">Live</option>
             </select> 
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Match Format:</label>
             <select class="form-control" name="format" required=""> 
              <option value="1">ODI</option>
                <option value="3">T20(International)</option> 
                <option value="6">T20(Domestic)</option> 
                <option value="7">Women ODI</option> 
                <option value="8">Women T20</option> 
                <option value="17">T10</option> 
             </select> 
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label" >Record Per Page:</label>
            <input type="text" class="form-control" id="record_per_page" value=""   name="record_per_page">
          </div>


           <div class="form-group">
            <label for="recipient-name" class="col-form-label" >Page Number:</label>
            <input type="number" class="form-control " id="paged"   name="paged">
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