
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
                                                <a href="#" data-toggle="modal" data-target="#notification"> 
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Create notification</button> 
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
                                <div class="portlet-body">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <form action="{{route('notification')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search " type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('notification') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr>
                                                 <th>Sno.</th>
                                                <th>  Title </th>
                                                <th> Message </th> 
						                        <th> Message Type  </th> 
                                                 
                                                <th> date</th> 
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($notification as $key => $result)
                                            <tr>
                                             <th>  {{++$key}} </th>
                                                <td> {{$result->title}} </td>
                                                 <td> {{$result->message}} </td>
                                                 <td> {{$result->message_type}} </td>
                                                    <!-- <td>  <a href="{{ route('notification.show',$result->id)}}">
                                                            <i class="fa fa-eye" title="details"></i> 
                                                        </a> </td>  -->
                                                     <td>
                                                        {!! Carbon\Carbon::parse($result->created_at)->format('d-m-Y'); !!}
                                                    </td>
                                                    
                                                    <td> 
                                                        <a href="{{ route('notification.edit',$result->id)}}">
                                                            <i class="fa fa-edit" title="edit"></i> 
                                                        </a>

                                                        {!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('notification.destroy', $result->id))) !!}
                                                        <button class='delbtn btn btn-danger btn-xs' type="submit" name="remove_levels" value="delete" id="{{$result->id}}"><i class="fa fa-fw fa-trash" title="Delete"></i></button>
                                                        
                                                         {!! Form::close() !!}

                                                    </td>
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
                                    <span>
                                     
                                     <div class="center" align="center">  {!! $notification->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}</div>
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
      
</div>


<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notify User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <div class="modal-body">

{!! Form::model($notification, ['route' => ['notification.store'],'class'=>'form-horizontal user-form','id'=>'user-form','enctype'=>'multipart/form-data']) !!}

<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
        <div class="form-group {{ $errors->first('message_type', ' has-error') }}">
            <label class="control-label col-md-3">Message Type <span class="required"> * </span></label>
            <div class="col-md-7"> 
                <select class="form-control" name="message_type"> 
                        <option value="notify">Notify</option>
                </select>
                
                <span class="help-block">{{ $errors->first('message_type', ':message') }}</span>
            </div>
        </div> 
 
        <div class="form-group {{ $errors->first('title', ' has-error') }}">
            <label class="control-label col-md-3">Title <span class="required"> * </span></label>
            <div class="col-md-7"> 
                {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!} 
                
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div> 

          <div class="form-group {{ $errors->first('message', ' has-error') }}">
            <label class="control-label col-md-3">Message<span class="required"> </span></label>
            <div class="col-md-7"> 
                {!! Form::textarea('message',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
                
                <span class="help-block">{{ $errors->first('message', ':message') }}</span>
            </div>
        </div>

    
        
    <div class="form-actions">
        <div class="row" style="padding-right:12px">
            <div class="col-md-10">
              {!! Form::submit(' Send Notification ', ['class'=>'btn  btn-success pull-right','id'=>'saveBtn']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}   


   </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

