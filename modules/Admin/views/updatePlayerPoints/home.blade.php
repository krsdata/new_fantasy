
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
                                     <div class="col-md-2 pull-right">
                                            <div style="width: 150px;" class="input-group"> 
                                                <a href="{{ route('updatePlayerPoints.create')}}">
                                                    <button class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Points</button> 
                                                </a>
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
                                <div class="portlet-body table-responsive">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <form action="{{route('updatePlayerPoints')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  Match Id or PID" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('updatePlayerPoints') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     <div class="" id="update_msg"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable">
                                        <thead>
                                            <tr>
                                        <th style="display: none;">#</th>

                                           @foreach($tables as $col_name)
                                           <th>

                            {{  \Str::replaceFirst('_'," ",ucfirst($col_name)) }}
                             
                                                </th> 
                                            @endforeach
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
            @foreach($updatePlayerPoints as $key => $result)
                <tr> 
                     <td class="tabledit-view-mode" style="display: hidden">{{$result->id}}</td>
                    @foreach($tables as $col_name)
                           <td class="tabledit-view-mode">{!!$result->$col_name!!}
                            @if($col_name=='pid')
                             <a href="{{ route('updatePlayerPoints.edit',$result->id)}}">
                            <i class="fa fa-fw fa-edit" title="edit"></i>
                        </a>
                            @endif
                           </td>
                    @endforeach
                   
                        
                    <td> 
                       <!--  <a href="{{ route('updatePlayerPoints.edit',$result->id)}}">
                            <button class="btn btn-success btn-xs">
                            <i class="fa fa-fw fa-edit" title="edit"></i> 
                            </button>
                        </a>
 
                        <hr> -->
                        {!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('updatePlayerPoints.destroy', $result->id))) !!}
                        <button class='delbtn btn btn-danger btn-xs' type="submit" name="remove_levels" value="delete" id="{{$result->id}}"><i class="fa fa-fw fa-trash" title="Delete"></i></button>
                        
                         {!! Form::close() !!}

                    </td>
                   
                </tr>
               @endforeach
                
            </tbody>
        </table>
<span>
  Showing {{($updatePlayerPoints->currentpage()-1)*$updatePlayerPoints->perpage()+1}} to {{$updatePlayerPoints->currentpage()*$updatePlayerPoints->perpage()}}
  of  {{$updatePlayerPoints->total()}} entries 
</span>

         <div class="center" align="center">  {!! $updatePlayerPoints->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}</div>
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
        




<style type="text/css">
     .mt-100 {
     margin-top: 100px
 }

 .container-fluid {
     margin-top: 50px
 }

 body {
     background-color: #f2f7fb
 }

 .card {
     border-radius: 5px;
     -webkit-box-shadow: 0 0 5px 0 rgba(43, 43, 43, 0.1), 0 11px 6px -7px rgba(43, 43, 43, 0.1);
     box-shadow: 0 0 5px 0 rgba(43, 43, 43, 0.1), 0 11px 6px -7px rgba(43, 43, 43, 0.1);
     border: none;
     margin-bottom: 30px;
     -webkit-transition: all 0.3s ease-in-out;
     transition: all 0.3s ease-in-out
 }

 .card .card-header {
     background-color: transparent;
     border-bottom: none;
     position: relative
 }

 .card .card-block {
 }

 .table-responsive {
     display: inline-block;
     width: 100%;
     overflow-x: auto
 }

 .card .card-block table tr {
     padding-bottom: 20px
 }

 .table>thead>tr>th {
     border-bottom-color: #ccc
 }

 .table2 th {
     padding: 1.25rem 0.75rem
 }

 td,
 th {
     white-space: nowrap
 }

 .tabledit-input:disabled {
     display: none
 }

</style>
