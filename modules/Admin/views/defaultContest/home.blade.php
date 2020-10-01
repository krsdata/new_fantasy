
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
                                                <a href="{{ route('defaultContest.create')}}">
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Create Contest</button> 
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
                                            <form action="{{route('defaultContest')}}" method="get" id="filter_data">


        <div class="col-md-3">
                    
                 {{ Form::select('contest_type',$contest_type, $_GET['contest_type']??0, ['class' => 'form-control','onchange'=>"this.form.submit()"]) }}
        </div> 

                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search " type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('defaultContest') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="contact">
                                        <thead>
                                            <tr>
                                                 <th>Sno.</th>
                                                <th> Contest type </th>
                                                <th> Cancellation </th>
                                                <th> Is Free </th>
                                                <th> Entry fees </th>
                                                <th> Total spots </th>
                                                <th> First prize </th>
                                                <th> Winner percentage </th>
                                                <th> Total winning prize </th> 
                                                  
                                                <th>Prize Breakup</th> 
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($defaultContest as $key => $result)
                                            <tr>
                                                <th>  {{++$key}} </th>
                                                <td> {{$contest_type[$result->contest_type]}}
                                                    {!!$result->match_id?'<span class="required" aria-required="true"> * </span>':''!!}
                                                 </td>
                                                  <th>  {{$result->cancellation?'Yes':'No'}} </th>

                                                <th>  
                                                    {{$result->is_free?'Yes':'No'}}
                                                </th>    
                                                
                                                <td> {{$result->entry_fees}} </td>
                                                <td> {{$result->total_spots}} </td>
                                                <td> {{$result->first_prize}} </td>
                                                <td> {{$result->winner_percentage}} </td>
                                                <td> {{$result->total_winning_prize}} </td>

                                                <td>
                                                <a href="{{route('defaultContest.show',$result->id)}}">Add Prize Breakup</a>
                                                </td>
                                                    
                                                <td> 
                                                    <a href="{{ route('defaultContest.edit',$result->id)}}">
                                                        <i class="fa fa-edit" title="edit"></i> 
                                                    </a>

                                                    {!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('defaultContest.destroy', $result->id))) !!}
                                                    <button class='delbtn btn btn-danger btn-xs' type="submit" name="remove_levels" value="delete" id="{{$result->id}}"><i class="fa fa-fw fa-trash" title="Delete"></i></button>
                                                    
                                                     {!! Form::close() !!}

                                                </td>
                                               
                                            </tr>
                                           @endforeach
                                            
                                        </tbody>
                                    </table>
                                    <span>
                                        
                                     <div class="center" align="center">  {!! $defaultContest->appends(['search' => isset($_GET['search'])?$_GET['search']:'','contest_type'=>$_GET['contest_type']??null])->render() !!}</div>
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
        
      