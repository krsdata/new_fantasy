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
                                            <form action="{{route('transaction')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  name,email and order" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('transaction') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered " id="">
                                        <thead>
                                            <tr>
                                                <th> Sno. </th> 
                                                <th> User Name </th>
                                                <th> Editor Name </th>
                                                <th> Order ID </th> 
                                                <th> Amount </th> 
                                                <th> Payment Mode </th> 
                                                <th> Editor Status</th>
                                                <th> User Status</th>  
                                                <th> Admin Status</th> 
                                                <th> Action </th> 
                                                <th>Created date</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transaction as $key => $result)
                                      <tr>
                                      <td> {{ (($transaction->currentpage()-1)*15)+(++$key) }} 
                                      </td>
                                       <td> 
                                      @if(isset($result->user))
                                      {{$result->user->first_name}},{{$result->user->email}} 
                                      @endif
                                    </td>
                                      <td> 
                                        @if(isset($result->editor))
                                        {{$result->editor->first_name}},{{$result->editor->email}}
                                      @endif

                                       </td>
                                      <td>
                                        <a href="
                                        {{url('admin/postTask/'.$result->id)
                                        }}"
                                        > 
                                        {{$result->order_id}} </a> </td>
                                      <td>{{$result->total_price}}
                                        @if(isset($result->payment))
                                      <span class="glyphicon glyphicon-ok"></span>
                                        @endif
                                      </td>
                                      <td>{{$result->payment_mode}}</td>
                                      <td>{{$result->editor_status}}</td>
                                      <td>{{$result->order_status}}</td>
                                      <td>{{$result->status}}</td>                         
                                      <td>  
                                                   
    <ul class="nav navbar-nav">
       
        <li class="dropdown ">
          <a href="#" class="dropdown-toggle btn-danger btn btn-sm" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Payment Action <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <li role="separator" class="divider"></li>
            <li><a href="#"   data-toggle="modal" data-target="#myModal" onclick="getCategory('{{$result->id}}')">Release Fund</a></li>
            <li role="separator" class="divider"></li> 
          </ul>
        </li>
      </ul>

                                                     
                                                 </td>
                                                <td>
                                                        {!! Carbon\Carbon::parse($result->created_at)->format('d-m-Y'); !!}
                                                </td>
                                          
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
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
        <input type="hidden" name="order_id" value="" id="order_id"> 
         <label>Amount</label>
         <input type="number" class="form-control" required="" name="amount" id="amount">
         <label>Service Charge</label>
         <input type="number" class="form-control"  required="" name="service_charge" id="service_charge">
             
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
    
    function getCategory(order_id) {
        document.getElementById("order_id").value  = order_id; 
    }
</script>

@stop