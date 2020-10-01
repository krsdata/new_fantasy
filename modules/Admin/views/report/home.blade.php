
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
                      


                      <!--  <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#getOldMatch" data-whatever="@" style="margin-right: 10px">Get Old Match</button> -->
                       <a href="{{url('admin/getMatchReport')}}">
                       <button type="button" class="btn pull-right btn-primary" style="margin-right: 10px">Get All Match Reports</button> 
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
                          <span data-counter="counterup" data-value="276">{{$total_completed_match??0}}</span>
                      </h3>
                      <small>Total Completed Match</small>
                  </div>
                  <div class="icon">
                      <i class="icon-user"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_completed_match}}%;" class="progress-bar progress-bar-success purple-soft">
                  </div>
                   
              </div>
          </div>
      </div>

      

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{$total_cancel_match}}</span>
                      </h3>
                      <small> Total Cancel Match </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_cancel_match}}%;" class="progress-bar progress-bar-success blue-sharp">
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
                          <span data-counter="counterup" data-value="567">{{$total_contest_played??0}}</span>
                      </h3>
                      <small> Total Contest Played </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div> 
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_contest_played??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          
                  </div>
                  
              </div>
          </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{round($amount_recieved_from_match,2)}} INR</span>
                      </h3>
                      <small> Amount recieved from match</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div> 
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$amount_recieved_from_match??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          
                  </div>
                  
              </div>
          </div>
      </div> 
       <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat2 bordered">
              <div class="display">
                  <div class="number">
                      <h3 class="font-blue-sharp">
                          <span data-counter="counterup" data-value="567">{{round($total_prize_distributed,2)}} INR</span>
                      </h3>
                      <small> Total Prize Distributed </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_prize_distributed??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($prize_distributed_this_month,2)}} INR</span>
                      </h3>
                      <small> Prize Distributed This Month </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$prize_distributed_this_month??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_profit,2)}} INR</span>
                      </h3>
                      <small> Total Profit </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_profit??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{$total_loss??0}}</span>
                      </h3>
                      <small> Total Loss </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_loss??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_bonus_given,2)}} INR</span>
                      </h3>
                      <small> Total Bonus Given </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_bonus_given??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_available_bonus,2)}} INR</span>
                      </h3>
                      <small> Total Available Bonus </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_available_bonus??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($registration_bonus,2)}} INR</span>
                      </h3>
                      <small> Registration Bonus Given </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$registration_bonus??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($referral_bonus,2)}} INR</span>
                      </h3>
                      <small> Referral Bonus Given </small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$referral_bonus??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_deposit,2) }}INR</span>
                      </h3>
                      <small> Total Deposit</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_deposit??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_deposit_this_month,2) }} INR</span>
                      </h3>
                      <small> Total Deposit This Month</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_deposit_this_month??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_deposit_today,2) }} INR</span>
                      </h3>
                      <small> Total Deposit Today</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_deposit_today??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_wallet_amount,2) }} INR</span>
                      </h3>
                      <small> Total Wallet Amount</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_wallet_amount??0}}%;" class="progress-bar progress-bar-success blue-sharp">
                          <span class="sr-only">6% grow</span>
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
                          <span data-counter="counterup" data-value="567">{{round($total_withdrwal_given,2) }} INR</span>
                      </h3>
                      <small> Total Withdral Given</small>
                  </div>
                  <div class="icon">
                      <i class="fa fa-folder-open-o"></i>
                  </div>
              </div>
              <div class="progress-info">
                  <div class="progress">
                      <span style="width: {{$total_withdrwal_given??0}}%;" class="progress-bar progress-bar-success blue-sharp">
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