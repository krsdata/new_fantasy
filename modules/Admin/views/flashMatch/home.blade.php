
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
                      <span class="caption-subject font-red sbold uppercase">All   Flash Matches</span>
                  </div>
                   <div class="col-md-12 pull-right">
                      
                      
                      <div class=" pull-right">
                          <div   class="input-group"> 
                                 <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#upload_match" data-whatever="@" style="margin-right: 10px">Upload Match</button> 
                          </div>
                      </div>

                      <div class=" pull-right">
                          <div   class="input-group"> 
                                 <button type="button" class="btn pull-right btn-success" data-toggle="modal" data-target="#upload_player" data-whatever="@" style="margin-right: 10px">Upload Player</button> 
                          </div>
                      </div>

                      <div class=" pull-right">
                          <div   class="input-group"> 
                                 <button type="button" class="btn pull-right btn-warning" data-toggle="modal" data-target="#upload_points" data-whatever="@" style="margin-right: 10px">Upload Player Points</button> 
                          </div>
                      </div>

                      <button type="button" class="btn pull-right btn-danger" data-toggle="modal" data-target="#playing11" data-whatever="@" style="margin-right: 10px">Upload Playing 11</button>

                       <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#changeMatchStatus" data-whatever="@" style="margin-right: 10px">Change Match Status</button> 

                      <!--  <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#getOldMatch" data-whatever="@" style="margin-right: 10px">Get Old Match</button> -->
                       <a href="{{url('admin/oldMatch')}}">
                       <button type="button" class="btn pull-right btn-primary" style="margin-right: 10px">Get Old Match</button> 
                     </a>
                       
                  </div>
              </div>
                
                  @if(Session::has('flash_alert_notice'))
                       <div class="alert alert-success alert-dismissable" style="margin:10px">
                          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="icon fa fa-check"></i>  
                       {{ Session::get('flash_alert_notice') }} 
                       </div>
                  @endif
                       
        
           <div class="row">
     
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-purple-soft">
                          <span data-counter="counterup" data-value="276">{{$total_flash_match}}</span>
                      </h3>
                      <small>Total Uploaded Match</small>
                  </div>
                  <div class="icon">
                      <i class="icon-user"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_flash_match}}%;" class="progress-bar progress-bar-success purple-soft">
                          
                  </div>
                   
              </div>
          </div>
      </div>

      

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{$total_live_match}}</span>
                      </h3>
                      <small> Total Live Match </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_live_match}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">2% grow</span>
                      </span>
                  </div>
                  
              </div>
          </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{$total_completed_match}}</span>
                      </h3>
                      <small> Total Completed Match </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div> 
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_completed_match}}%;" class="progress-bar progress-bar-success blue-sharp">
                          
                  </div>
                  
              </div>
          </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{$total_upcoming_match}}</span>
                      </h3>
                      <small> Total Upcoming  Match</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div> 
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_upcoming_match}}%;" class="progress-bar progress-bar-success blue-sharp">
                          
                  </div>
                  
              </div>
          </div>
      </div> 
                         <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-blue-sharp">
                                            <span data-counter="counterup" data-value="567">{{$total_cancel_match??0}}</span>
                                        </h3>
                                        <small> Total Cancel match </small>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-folder-open-o"></i>
                                    </div>
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: {{$total_cancel_match??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                                            <span class="sr-only">6% grow</span>
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div> 
                    <!-- END PAGE BASE CONTENT --> 
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
            <input type="text" class="form-control s_date" id="start_date" value="{{date('Y-m-d')}}" readonly name="date_start">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label" >End Date:</label>
            <input type="text" class="form-control e_date" id="end_date" value="{{date('Y-m-d')}}" readonly name="date_end">
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
                <option value="6">T20</option> 
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

<!-- start match -->
 
<!-- End status -->

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

<div class="modal fade" id="upload_match" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Match</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{url('admin/flashMatch')}}" accept-charset="UTF-8" class="" id="users_form" enctype="multipart/form-data">  
        @csrf 
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Upload Match Json File</label>
            <input type="file" class="form-control" id="match_id"  name="match_json" >
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

<div class="modal fade" id="upload_player" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Player</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{url('admin/flashMatch')}}" accept-charset="UTF-8" class="" id="users_form" enctype="multipart/form-data">  
        @csrf 
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Upload Player Json File</label>
            <input type="file" class="form-control" id="match_id"  name="player_json" >
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

<div class="modal fade" id="upload_points" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Points</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{url('admin/flashMatch')}}" accept-charset="UTF-8" class="" id="users_form" enctype="multipart/form-data">  
        @csrf 
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Upload Player Points Json File</label>
            <input type="file" class="form-control" id="match_id"  name="point_json" >
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


<div class="modal fade" id="playing11" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Playing 11</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{url('admin/flashMatch')}}" accept-charset="UTF-8" class="" id="users_form" enctype="multipart/form-data">  
        @csrf 
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Upload Player 11 Json File</label>
            <input type="file" class="form-control" id="match_id"  name="playing11_json" >
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