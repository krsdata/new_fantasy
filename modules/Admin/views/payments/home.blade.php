
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
                                        <span class="caption-subject font-red sbold uppercase">{{$heading}}</span>
                                    </div>
                                      
                                     
                                </div>
                                  
                                    @if(Session::has('flash_alert_notice') || isset($msg))
                                         <div class="alert alert-success alert-dismissable" style="margin:10px">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                          <i class="icon fa fa-check"></i>  
                                         {{ Session::get('flash_alert_notice') }} 
                                         {{$msg??null}}
                                         </div>
                                    @endif
                                <div class="portlet-body table-responsive">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <form action="{{route('payments')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  name" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('payments') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th> Sno. </th>
                                                <th>   Name </th>
                                                <th>Bank details</th>
                                                <th>Transaction</th>
                                                <th> Available Balance</th> 
                                                <th> Request Amount </th> 
                                                <th> Status</th>  
                                                <th> </th> 
                                                <th>Date</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @if($transaction->count()==0)
                                          <div class="alert alert-danger alert-dismissable" style="margin:10px">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                          <i class="icon fa fa-check"></i>  
                                            No Payment request found
                                         </div>
                                          
                                          @endif
                                        @foreach($transaction as $key => $result)
                                            <tr>
                                                 <td>{{ (($transaction->currentpage()-1)*15)+(++$key) }} 
                                                </td>
                                                <td> 
                                                  <a href="{{url('admin/user?search='.$result->email)}}" target="_blank"> 
                                                  {!!'Customer Id: '.$result->user_id.'<br>'.$result->name!!},
                                                  <br>{{$result->email}}
                                                </a>
                                                   </td>
                                                   <td>
                                                    <a href="{{url('admin/documents?search='.$result->email)}}" target="_blank">
                                                   View Account
                                                 </a>
                                                 </td>

                                                 <td>
                                                    <a href="{{url('admin/wallets?search='.$result->email)}}" target="_blank">
                                                   View Wallet
                                                 </a>
                                                 </td>

                                                <td>INR {{$result->total_balance-$result->paid_balance}} </td>
                                                 <td>INR {{$result->amount}} </td>
                                                 <td>{{$result->withdraw_status}}
                                                 </td>                     
                                                <td>  
                                                   
     <ul class="nav navbar-nav">
       
        <li class="dropdown ">
          <a href="#" class="dropdown-toggle btn-danger btn btn-sm" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Payment Action <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{url('admin/payments?status=2&txt_id='.$result->id)}}">Payment Initiated</a></li>
            <li><a href="{{url('admin/payments?status=3&txt_id='.$result->id)}}">Payment Hold</a></li>
            <!-- <li><a href="{{url('admin/payments?status=4&txt_id='.$result->id)}}">Payment Refund</a></li> -->
            <li role="separator" class="divider"></li>
            <li><a href="#"   data-toggle="modal" data-target="#myModal" onclick="payment('{{$result->id}}',{{$result->amount}})">Release Fund</a></li>
            <li role="separator" class="divider"></li> 
          </ul>
        </li>
      </ul>

                                                     
                                                 </td>
                                                <td>
                                                        {!! Carbon\Carbon::parse($result->updated_at)->format('d M Y'); !!}
                                                </td>
                                                
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
<span>
  Showing {{($transaction->currentpage()-1)*$transaction->perpage()+1}} to {{$transaction->currentpage()*$transaction->perpage()}}
  of  {{$transaction->total()}} entries 
</span>

                                     <div class="center" align="center">  {!! $transaction->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}</div>
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
        
        <!-- Modal -->
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Fund to Editor Wallets</h4>
      </div>
      <form method="post">
      <div class="modal-body">
        <input type="hidden" name="payment_id" value="" id="payment_id"> 
         <label>Amount</label>
         <input type="number" class="form-control" required="" readonly="" name="amount" id="amount">
         <label>Remarks</label>
         <textarea class="form-control"  required="" name="remarks" id="service_charge"></textarea>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-danger"  > Save </button>
      </div>
      </form>
    </div>

  </div>
</div>



<script type="text/javascript">
    
    function payment(payment_id,amount) {
        document.getElementById("payment_id").value     = payment_id;
        document.getElementById("amount").value         = amount;


    }
</script>