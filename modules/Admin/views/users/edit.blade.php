
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
                    
                      @include('packages::partials.breadcrumb')

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet bordered">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic">
                                    @if(!empty($user->profile_image))
                                    
                                     <img src="{{$user->profile_image}}" class="img-responsive" alt=""> </div>
                                    @else
                                     <img src="{{ URL::asset('assets/img/user.png')}}" class="img-responsive" alt=""> </div>
                                    @endif
                                      
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name"> {{$user->name}} </div>
                                        <div class="profile-usertitle-job"> {{$user->position}} </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                    <!-- SIDEBAR BUTTONS -->
                                    <div class="profile-userbuttons">
                                        <button type="button" class="btn btn-circle green btn-sm">{{$user->email}}</button>
                                        <button type="button" class="btn btn-circle red btn-sm">{{$user->mobile_number}}</button>
                                      
                                    </div>
                                    <!-- END SIDEBAR BUTTONS -->
                                    <!-- SIDEBAR MENU -->
                                    <div class="profile-usermenu">
                                        <ul class="nav">
                                            
                                            <!-- <li>
                                                <a href="#">
                                                    <i class="icon-info"></i> Help </a>
                                            </li> -->
                                        </ul>
                                    </div>
                                    <!-- END MENU -->
                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light bordered">
                                    <!-- STAT -->
                                   
                                    <!-- END STAT -->
                                    <div>
                                        <h4 class="profile-desc-title">About Match</h4>
                                          <div class="row list-separated profile-stat">
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$match_id??0}} </div>
                                            <div class="uppercase profile-stat-text">  Match </div>
                                        </div> 
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$contest_id??0}} </div>
                                            <div class="uppercase profile-stat-text"> contest </div>
                                        </div>
                                         <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$win??0}} </div>
                                            <div class="uppercase profile-stat-text"> WIN </div>
                                        </div>
                                         
                                    </div>
                                        <span class="profile-desc-text">{{$user->about_me}}</span>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-phone"></i>
                                            {{$user->phone}}
                                        </div>
                                       <!--  <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-twitter"></i>
                                            <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                                        </div>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-facebook"></i>
                                            <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                                        </div> -->
                                    </div>
                                </div>


                                <div class="portlet light bordered">
                                    <!-- STAT -->
                                   
                                    <!-- END STAT -->
                                    <div>
                                        <h4 class="profile-desc-title">Referral : {{$user->referal_code}}</h4>
                                          <div class="row list-separated profile-stat">
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$referral??0}} </div>
                                            <div class="uppercase profile-stat-text">  Total Referral </div>
                                        </div> 
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$referral*3??0}} </div>
                                            <div class="uppercase profile-stat-text"> Earning </div>
                                        </div>
                                         
                                         
                                    </div>
                                        <span class="profile-desc-text">{{$user->about_me}}</span>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-phone"></i>
                                            {{$user->phone}}
                                        </div>
                                       <!--  <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-twitter"></i>
                                            <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                                        </div>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-facebook"></i>
                                            <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                                        </div> -->
                                    </div>
                                </div>


                                <div class="portlet light bordered">
                                    <!-- STAT -->
                                   
                                    <!-- END STAT -->
                                    <div>
                                        <h4 class="profile-desc-title">Wallets : INR {{round($prize+$deposit+$referral*3,2)}}</h4>
                                          <div class="row list-separated profile-stat">
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="  profile-stat-title">   {{round($prize??0,2)}} </div>
                                            <div class="uppercase profile-stat-text"> Prize </div>
                                        </div> 
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="  profile-stat-title">   {{round($deposit??0,2)}} </div>
                                            <div class="uppercase profile-stat-text"> Deposit </div>
                                        </div>
                                         
                                         
                                    </div>
                                         
                                         
                                       <!--  <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-twitter"></i>
                                            <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                                        </div>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-facebook"></i>
                                            <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                                        </div> -->
                                    </div>
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light bordered">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#Document" data-toggle="tab">Document</a>
                                                    </li>
                                                   
                                                </ul>
                                            </div>
                                   {!! Form::model($user, ['method' => 'PATCH', 'route' => ['user.update', $user->id],'enctype'=>'multipart/form-data']) !!}
                                   <input type="hidden" name="role" value="{{$_REQUEST['role_type']}}"> 
                                    <div class="portlet-body">
                                        <div class="tab-content">
                                            <!-- PERSONAL INFO TAB --> 
                                                <div class="margin-top-10">
                                                    @if (count($errors) > 0)
                                                      <div class="alert alert-danger">
                                                          <ul>
                                                              @foreach ($errors->all() as $error)
                                                                  <li>{!! $error !!}</li>
                                                              @endforeach
                                                          </ul>
                                                      </div>
                                                    @endif
                                                </div>

                                            @include('packages::users.formTab1', compact('user'))

                                            
                                            <!-- END PERSONAL INFO TAB --> 
                                           
                                            <!-- END CHANGE AVATAR TAB -->
                                            <!-- CHANGE PASSWORD TAB -->
                                            <!-- END CHANGE PASSWORD TAB -->
                                            <!-- PRIVACY SETTINGS TAB --> 
                                            @include('packages::users.Document', compact('user')) 
                                            <!-- END PRIVACY SETTINGS TAB --> 
                                            {!! Form::close() !!} 
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                </div>
                <!-- END CONTENT BODY -->
            </div>
            
            
            <!-- END QUICK SIDEBAR -->
        </div>
        

        
@stop