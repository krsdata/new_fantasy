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
                                    <div class="col-md-12 pull-right">
                                        <div class=" pull-right">
                                            <div   class="input-group"> 
                                                <a href="{{ route('content')}}">
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Page Details</button> 
                                                </a> 
                                            </div>
                                        </div>
                                        <div class="col-md-2 pull-right"> 
                                             <div  class="input-group pull-right"> 
                                                
                                                 <a href="{{ route('content.edit',$id)}}">
                                                    <button  class="btn btn-success"><i class="fa fa-plus-circle"></i> Edit Page</button> 
                                                </a>
                                            </div>
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
                                    <table class="table table-striped table-hover table-bordered" id="contact">  
                                        <tbody>
                                            @foreach($page as $key => $result)
                                            <tr>
                                                @if($key=='created_at') 
                                                <th>  Created Date </th>
                                                <td>  
                                                     {!! Carbon\Carbon::parse($result)->format('m-d-Y'); !!}

                                                </td> 
                                                 @else
                                                <th>  {{ str_replace('_',' ',ucfirst($key)) }} </th>
                                                <td> {!! str_replace('_',' ',ucfirst($result)) !!} </td>
                                                 @endif  
                                            </tr>
                                           @endforeach 
                                        </tbody>
                                    </table>  
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
@stop