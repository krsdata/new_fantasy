 <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
<?php 
    $route  =   Route::currentRouteName();

?>
<style type="text/css">
    
.scrollbar
{
     
  /*  float: left; 
    overflow-y: scroll;
    overflow-x: hidden; */
}
#scroll_bar::-webkit-scrollbar-thumb
{
    background-color: red;
    border-radius: 10px;
    background-image: -webkit-linear-gradient(0deg,
                                              rgba(255, 255, 255, 0.5) 25%,
                                              transparent 25%,
                                              transparent 50%,
                                              rgba(255, 255, 255, 0.5) 50%,
                                              rgba(255, 255, 255, 0.5) 75%,
                                              transparent 75%,
                                              transparent)
}
</style>

<div class="page-container">
 <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
        <div id="scroll_bar" class="page-sidebar scrollbar navbar-collapse collapse" style="overflow-y: scroll !important; max-height: 700px !important ">

            <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start active open">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start active open">
                            <a href="{{ url('admin')}}" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        </ul>
                </li>
                @foreach($main_menu as $key => $result)
                <li class="nav-item start  @if($route==$result->title) active open @endif @if($route==$result->title.'.create') active open @endif">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="glyphicon glyphicon-th"></i>
                                <span class="title">{{ucfirst($result->title)}}</span>
                                <span class="arrow @if($route==$result->title) open @endif"></span>
                            </a>
                            <ul class="sub-menu" style="display: @if($route==$result->title) block @endif">
                                
                                @foreach($result->sub_menu as $key => $sub_menu)

                                @if(\Str::contains($sub_menu->title, 'Create'))

                            <li class="nav-item  @if($route==$result->title.'.create') active @endif">
                                
                                <a href="{{ route($result->title.'.create') }}" class="nav-link ">
                                       <i class="glyphicon glyphicon-eye-open"></i>
                                        <span class="title">
                                        {{ $sub_menu->title }}
                                        </span>
                                    </a>
                                </li> 
                                @else
                                 <li class="nav-item  @if($route==$result->title) active @endif">
                                    <a href="{{ route($result->title) }}" class="nav-link ">
                                       <i class="glyphicon glyphicon-eye-open"></i>
                                        <span class="title">
                                        {{ $sub_menu->title }}
                                        </span>
                                    </a>
                                </li> 
                                @endif
                                @endforeach

                            </ul>
                </li>
                @endforeach  
            </ul>  
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div> 
