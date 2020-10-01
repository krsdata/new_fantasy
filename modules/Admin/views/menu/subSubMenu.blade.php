<ul class="sub-menu">
@foreach($childs as $key => $child)
	 <li class="nav-item start">
	    <a href="{{ route('menu.edit',$child->id)}}">
            <i class="fa fa-edit" title="edit"></i> 
        </a>
	    <a href="javascript:;" class="nav-link nav-toggle">
    	<span class="title"><span class="glyphicon glyphicon-th-list"></span>{{ $child->title }}</span>
    	<span class="arrow"></span>
		</a>
	     @if(count($child->childs))
            @include('packages::menu.subSubMenu',['childs' => $child->childs])
        @endif

	</li>
@endforeach
</ul>
 