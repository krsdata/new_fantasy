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
                                        <span class="caption-subject font-red sbold uppercase">Payments History</span>
                                    </div>

                                    <div class="col-md-2 pull-right">
                                                <input type="submit" value="Today  : {{round($today,2)}}" class="btn btn-info form-control">
                                            </div>
                                          <div class="col-md-2 pull-right">
                                                <input type="submit" value="Monthly  : {{round($month,2)}}" class="btn btn-info form-control">
                                            </div>
                                            <div class="col-md-2 pull-right">
                                                <input type="submit" value="Weekly : {{round($week,2)}}" class="btn btn-info form-control">
                                            </div>
                                            <div class="col-md-3 pull-right">
                                                <input type="submit" value="Total Deposit : {{round($deposit,2)}}" class="btn btn-info form-control">
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
                                            <form action="{{route('paymentsHistory')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  name" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('paymentsHistory') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table-responsive table  table-condensed table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th> Sno. </th>
                                                <th> Txt ID </th>
                                                <th> Name </th>
                                                <th>Type</th>
                                                <th>   Amount </th>  
                                                <th> Status</th>
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
                                                 <td>   {{ (($transaction->currentpage()-1)*15)+(++$key) }} 
                                                </td>
                                                <td>{{$result->transaction_id}} </td>
                                                <td>
                                                  <a href="{{url('admin/user?search='.$result->email)}}"> 
                                                   ID: {{$result->user_id}},<br>
                                                   
                                                   Name: {{$result->name}},<br>
                                                   Email : {{$result->email}}
                                                   <br>
                                                   Phone : {{$result->phone}}
                                                 </a>
                                                    </td>
                                                
                                                 <td>{{$result->payment_type_string}} </td>
                                                 <td>
                                                  @if($result->debit_credit_status=="+")
                                                  <div class=" alert-success"> {{' '.$result->debit_credit_status.' '.$result->amount}} INR</div>
                                                  @else
                                                  <div class=" alert-danger">
                                                  {{' '.$result->debit_credit_status.' '.$result->amount}} INR
                                                </div>
                                                  @endif
                                                   </td>
                                                 
                                                 <td>{{$result->payment_status}}
                                                 </td>                         
                                                
                                                <td>
                                                        {!! Carbon\Carbon::parse($result->updated_at)->format('d-m-Y h:i:s'); !!}
                                                </td>
                                                
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
<span>
  Showing {{($transaction->currentpage()-1)*$transaction->perpage()+1}} to {{$transaction->currentpage()*$transaction->perpage()}}
  of  {{$transaction->total()}} entries 
</span>

                                     <div class="center" align="center">  {!! $transaction->appends(['search' => isset($_GET['search'])?$_GET['search']:'','payment_type'=>$_GET['payment_type']??''])->render() !!}</div>


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


@stop