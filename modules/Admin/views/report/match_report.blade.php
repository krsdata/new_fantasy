<style type="text/css">
  table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}
</style>

            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEAD-->
                    
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE BREADCRUMB --> 

                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                      <center><div>SPORTSFIGHT</div></center>
                      <hr>
                        <div class="row">
                            <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light portlet-fit bordered">
                                <div class="portlet-body table-responsive">
                                    
                                    <div>Match Report Generated Date: {{date('d-m-Y, h:i A')}}</div>
                                    <table class="table table-striped table-hover table-bordered" id="contact" border="1">
                                        <thead>
                                            <tr>
                                                <th> Sno.</th>
                                                <th> Match Id</th>
                                                <th> Match Name </th>  
                                                <th> Total Contest</th>
                                                <th> Contest Joined</th>
                                                <th> User Played</th>
                                                <th> Prize Given</th>
                                                <th>Amount Recieved</th>
                                                <th> Status</th> 
                                                <th> Date </th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @if(!$match->count())
                                          Record not found
                                          @endif
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
                                                <th>
                                                  {{$result->total_amt_rcv}}</th>
                                                
                                               
                                      <td> {{$result->status_str}} </td>
                                      <td>{{
                                            date('d,M Y, h:i A',$result->timestamp_start)
                                          }}
                                      </td>
                                         
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                            <br><br>
                             <div>**Note: This is computer generated report hence signature is not required**</div>
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