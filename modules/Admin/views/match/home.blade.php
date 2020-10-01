
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
                    <!-- BEGIN PAGE HEAD-->
    @include('packages::partials.breadcrumb')

    <div class="row">
          <div class="col-md-12">
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet light portlet-fit bordered">
              <div class="portlet-title">
                  <div class="caption">
                      <i class="icon-settings font-red"></i>
                      <span class="caption-subject font-red sbold uppercase">All Matches </span>
                  </div>
                   <div class="col-md-12 pull-right">
                      <div class=" pull-right">
                          <div   class="input-group"> 
                              <a href="{{ route('match')}}?status=3">
                                  <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Live </button> 
                              </a> 
                          </div>
                      </div>
                      <div class=" pull-right">
                          <div   class="input-group"> 
                              <a href="{{ route('match')}}?status=2">
                                  <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Completed </button> 
                              </a> 
                          </div>
                      </div>
                      <div class=" pull-right">
                          <div   class="input-group"> 
                              <a href="{{ route('match')}}?status=1">
                                  <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Upcoming </button> 
                              </a> 
                          </div>
                      </div>

                      <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#changeDate" data-whatever="@" style="margin-right: 10px">Change Match Date</button> 

                       <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#changeMatchStatus" data-whatever="@" style="margin-right: 10px">Change Match Status</button> 
                       
                  </div>
                   
              </div>
                
                  @if(Session::has('flash_alert_notice'))
                       <div class="alert alert-success alert-dismissable" style="margin:10px">
                          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="icon fa fa-check"></i>  
                       {{ Session::get('flash_alert_notice') }} 
                       </div>
                  @endif
              <div class="portlet-body table-responsive" style="min-height: 480px">
                  <div class="table-toolbar">
                      <div class="row">
                          <form action="{{route('match')}}" method="get" id="filter_data">
                           
                          <div class="col-md-3">
                              <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search " type="text" name="search" id="search" class="form-control" >
                          </div>
                            <div class="col-md-3">
                              <select class="form-control" name="status">
                                  <option value="">Select Status</option>
                                  <option value="1" @if(isset($_REQUEST['status']) && $_REQUEST['status']==1) selected @endif>Upcoming</option>
                                   <option value="2" <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==2) { echo "selected"; }  ?>> Completed</option>
                                    <option value="3" @if(isset($_REQUEST['status']) && $_REQUEST['status']==3) selected @endif>Live</option>
                                    <option value="4" @if(isset($_REQUEST['status']) && $_REQUEST['status']==4) selected @endif>Cancelled</option>
                              </select>
                          </div>
                          <div class="col-md-2">
                            <input type="hidden" name="change_date" value="change_date">
          <div class="form-group">

            <input type="date" class="form-control  form_datetime" id="start_date" value="{{$_REQUEST['match_start_date']??'Search By Date'}}"   name="match_start_date">
          </div>
            </div>
            <div class="col-md-2">
                <input type="submit" value="Search" class="btn btn-primary form-control">
            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('match') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr>
                                                 <th>Sno.</th>
                                                <th> Match Id </th>
                                                <th> Match Between </th> 
                                                <th> Short title </th> 
                                                <th> Add Contest</th> 
                                                <th> Player List </th>  
                                                <th> Action</th> 
                                                <th> Status</th> 
                                                <th> Date </th> 
                                                <th> Prize Status</th>  
                                                <th> Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($match as $key => $result)
                                            <tr>
                                              <td>
                                               {{ (($match->currentpage()-1)*15)+(++$key) }}</td>

                                                <td> {{$result->match_id}} </td>

                                                 <td> {{$result->short_title}} 


                                                 </td>

                                                 <td> <a class="btn btn-success" href="{{env('api_base_url')}}/getSquadByMatch/{{$result->match_id}}?allowme=1">
                                                    update Squad
                                                 </a>
                                                  </td>
                                                 <td> <a class="btn btn-success" href="{{route('defaultContest.create')}}?match_id={{$result->match_id}}">
                                                    Add Contest
                                                 </a>
                                                  </td>
                                                 <td> 
<!-- 
                                                  <a class="btn btn-success" href="{{route('match.show',$result->id)}}?player={{$result->match_id}}">
                                                    View Players

                                                 </a> -->

<a class="dropdown-item btn btn-success" data-toggle="modal" data-target="#Players_{{$result->id}}" href="#">View Players</a>

<div class="modal fade" id="Players_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 100%">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="Players">Players</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <div class="modal-body portlet-body table-responsive">
        
  <table class="table table-bordered" width="100%">
  <thead>
    <tr> 
      <th scope="col">Wicket Keeper</th>
      <th scope="col">Bats Men</th>
      <th scope="col">All Rounder</th>
      <th scope="col">Bowler</th>
      <th scope="col"> Trump | Captain | Vice Cap </th> 
    </tr>
  </thead>
  <tbody>
    <tr>

       <td> 
        @if(isset($result->players['wk']))
         <table class="table table-bordered" width="100%">
          <tr>
            <td>Name</td>
            <td>Rating</td>
            <td>Points</td>
          </tr>
        @foreach($result->players['wk'] as $key => $wk)
          <tr>
            <td>
            <input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="players[]" id="players_{{$result->match_id}}" value="#">  
                <span></span>
            </label>
              {{  $wk->short_name }}
            
            <br>{!!  $wk->team_name !!}
            
            @if(in_array($wk->pid,$result->playin11))
             <span class="btn-success btn-xs">
                Playing
            </span>
            @endif
            
            </td>
            <td> {{$wk->fantasy_player_rating}}</td>
            <td> 100</td>
          </tr>
              @endforeach
        </table>
       @endif
     </td>
  
      <td> 
         @if(isset($result->players['bat']))
         <table class="table table-bordered" width="100%">
          <tr>
            <td>Name</td>
            <td>Rating</td>
            <td>Points</td>
          </tr>
        @foreach($result->players['bat'] as $key => $bat)
          <tr>
            <td>
            <input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="players[]" id="players_{{$result->match_id}}" value="#">  
                <span></span>
            </label>
            {{$bat->short_name}}  <br>
                {!!  $bat->team_name !!}
            @if(in_array($bat->pid,$result->playin11))
             <span class="btn-success btn-xs">
                Playing
            </span>
            @endif
            </td>
            <td> {{$bat->fantasy_player_rating}}</td>
            <td> 100</td>
          </tr>
              @endforeach
        </table>
       @endif

      </td>

      <td> 
          @if(isset($result->players['all']))
         <table class="table table-bordered" width="100%">
          <tr>
            <td>Name</td>
            <td>Rating</td>
            <td>Points</td>
          </tr>
        @foreach($result->players['all'] as $key => $all)
          <tr>
            <td>
            <input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="players[]" id="players_{{$result->match_id}}" value="#">  
                <span></span>
            </label>
            {{$all->short_name}} <br>
            
                {!!  $all->team_name !!}
             
            @if(in_array($all->pid,$result->playin11))
             <span class="btn-success btn-xs">
                Playing
            </span>
            @endif
            </td>
            <td> {{$all->fantasy_player_rating}}</td>
            <td> 100</td>
          </tr>
              @endforeach
        </table>
       @endif
      </td>

      <td> 
         @if(isset($result->players['bowl']))
         <table class="table table-bordered" width="100%">
          <tr>
            <td>Name</td>
            <td>Rating</td>
            <td>Points</td>
          </tr>
        @foreach($result->players['bowl'] as $key => $bowl)
          <tr>
            <td>
            <input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="players[]" id="players_{{$result->match_id}}" value="#">  
                <span></span>
            </label>
            {{$bowl->short_name}} <br>
            {!!  $bowl->team_name !!}
            @if(in_array($bowl->pid,$result->playin11))
             <span class="btn-success btn-xs">
                Playing
            </span>
            @endif
            </td>
            <td> {{$bowl->fantasy_player_rating}}</td>
            <td> 100</td>
          </tr>
              @endforeach
        </table>
       @endif
      </td>   
      
      <td> 
         @if(isset($result->players['bowl']))
         <table class="table table-bordered" width="100%">
          <tr>
            <td>Name</td>
            <td>T</td>
            <td>C</td>
            <td>VC</td>
          </tr>
        @foreach($result->players['bowl'] as $key => $bowl)
          <tr>
            <td> 
            {{$bowl->short_name}}
            </td>
            <td><input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="t" id="t" value="{{$bowl->pid}}">
                <span></span>
            </label></td>
            <td><input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="c" id="c" value="#">  
                <span></span>
            </label></td>
            <td><input type="hidden" name="match_id" value="{{$result->match_id}}">          
              <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="vc" id="vc" value="#">  
                <span></span>
            </label></td>
          </tr>
              @endforeach
        </table>
       @endif
      </td> 

    </tr>
     
  </tbody>
</table>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
</div>
</div>
</div>


                                                 
                                               </td>
                                               <td>    
    <style type="text/css">
      .dropdown-item{
        width: 200px;
        float: left;
      }
    </style>
    <div class="btn-group dropleft"> 
      <button class="btn btn-danger" type="button" data-toggle="dropdown">Action
      <span class="caret"></span></button>

      <div class="dropdown-menu">
        <a class="dropdown-item btn btn-primary" href="{{ route('match.show',$result->id)}}">View Details <i class="fa fa-eye" title="details"></i> </a>
        @if($result->status==2)


         <a class="dropdown-item btn btn-success" target="_blank" href="{{env('api_base_url')}}/prizeDistribution?allowme=true&match_id={{$result->match_id}}">
           Generate Prize
              </a> 
          @else
          <a class="dropdown-item btn btn-warning" href="#">Generate Prize - NA</a>
          @endif  
        <div class="dropdown-divider"></div>
        <a class="dropdown-item btn btn-danger" data-toggle="modal" data-target="#playing11_{{$result->id}}" href="#">Playing 11 Squad</a>

        <a class="dropdown-item btn btn-info" href="{{route('triggerEmail','match_id='.$result->match_id)}}">Prize Email Trigger</a>
        <a class="dropdown-item btn btn-success" href="{{env('api_base_url')}}/v2/affiliateProgram?match_id={{$result->match_id}}&allowme=1">Add Affiliate Commission</a>
           
          <a class="dropdown-item btn btn-danger" data-toggle="modal" data-target="#cancelContest_{{$result->id}}" href="#">Cancel Match Contest</a>


         <!-- <a class="dropdown-item btn btn-primary" href="{{route('cancelMatch','match_id='.$result->match_id)}}">Cancel This Match</a> -->


         <a class="dropdown-item btn btn-primary" href="{{route('matchContest','search='.$result->match_id)}}">View All Contests</a>
         
      </div>
    </div>

<div class="modal fade" id="cancelContest_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg  " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Cancel Match Contest</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <form action="{{route('cancelContest','match_id='.$result->match_id)}}"> 
      <div class="modal-body">

         <table class="table table-striped table-hover table-bordered" id="contact">
          <thead>
              <tr>
                  <th>Sno.</th>
                  <th> Contest Name</th> 
                  <th> Total Spot </th>  
                  <th> Filled Spot</th> 
                  <th> Remaining Spot</th>
                  <th> Entry Fees</th>
                  <th> Status</th> 
                  <th> Action </th> 
              </tr>

          </thead>
          <tbody>
            @foreach($result->contests as $key => $contest)
            <tr>
              <td>{{$key+1}} </td>
              <td>{{$contest->contest_name}}</td>
              <td>{{$contest->total_spots}}</td>
              <td>{{$contest->filled_spot??'0'}}</td>
              <td>
                <?php  

                  $count = ($contest->total_spots-$contest->filled_spot);
                  if($count<1){
                    echo "Unlimited Spot";
                  }else{
                    echo $count; 
                  }
               ?> </td>
               <td>{{$contest->entry_fees??'0'}}</td>
              <td>{{ ($contest->is_cancelled==0)?'Active':'Cancelled' }}  </td>
              <td>
                 <div class="mt-checkbox-list">
                  <input type="hidden" name="match_id" value="{{$result->match_id}}">
                  @if(($contest->is_cancelled==0))
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="cancel_contest[]" id="cancel_contest_{{$result->match_id}}" value="{{$contest->id}}">  
                        <span></span>
                    </label>
                    </div>
                    @endif
              </td>
            </tr>
            @endforeach
 
          </tbody>
      </table>  

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"> Cancel Selected Contest </button>
        </div>
      </div>
    </form>
</div>
</div>
</div>


<div class="modal fade" id="playing11_{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg  " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Playing 11</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <div class="modal-body">

         <table class="table table-striped table-hover table-bordered" id="contact">
          <thead>
              <tr>
                  <th>Sno.</th>
                  <th> Player Name</th> 
              </tr>

          </thead>
          <tbody>
            <tr>
              <td>Team A</td>
              <td>{{(count($result->playing11_teamA)==0)?'Not announced':''}}</td>
            </tr>
            @foreach($result->playing11_teamA as $key => $playing11)
            <tr>
              <td>{{$key+1}} </td>
              <td>{{$playing11->name}}</td>
            </tr>
            @endforeach
            <tr>
              <td>Team B</td>
              <td>{{(count($result->playing11_teamB)==0)?'Not announced':''}}</td>
            </tr>
             @foreach($result->playing11_teamB as $key => $playing11)
            <tr>
              <td>{{$key+1}} </td>
              <td>{{$playing11->name}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>  

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
</div>
</div>
</div>


                                              </td> 
                                             

                                         <td> {{$result->status_str}} </td>
                                         <td> 
                                          {{
                                            date('D d, M Y h:i A',$result->timestamp_start)
                                          }}
                                            
                                        </td>
                                         <td> 
                                            @if($result->current_status==1) 
                                             Prize Distributed 
                                            @else
                                             NA
                                            @endif
                                            </td> 
                        <td> 
                            <a href="{{ route('match.edit',$result->id)}}">
                              <button class="btn btn-success btn-xs">
                                <i class="fa fa-fw fa-edit" title="edit"></i>
                                Edit 
                              </button>
                            </a>
                            <br>
    <span style="margin-top: 5px; float: left" class="label label-{{ ($result->is_cancelled==0)?'success':'warning'}} status" id="{{$result->id}}"  data="{{$result->is_cancelled}}"  onclick="changeStatus({{$result->id}},'match')" >
                                            {{ ($result->is_cancelled==0)?'Active':'Inactive'}}
                                        </span>
                        </td>
                  </tr>







                                   @endforeach
                                    
                                </tbody>
                            </table>
                            <span>
                              Showing {{($match->currentpage()-1)*$match->perpage()+1}} to {{$match->currentpage()*$match->perpage()}}
                            of  {{$match->total()}} entries </span>
                             <div class="center" align="center">  {!! $match->appends(['search' => isset($_GET['search'])?$_GET['search']:'','status' => isset($_GET['status'])?$_GET['status']:''])->render() !!}</div>
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


