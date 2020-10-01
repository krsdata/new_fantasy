<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
   
 	<div class="form-group {{ $errors->first('title', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
        <label class="control-label col-md-3">  Main Menu <span class="required"> * </span></label>
        <div class="col-md-4"> 
            {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!} 
            
            <span class="help-block" style="color:red">{{ $errors->first('title', ':message') }} @if(session('field_errors')) {{ 'The  Title name already been taken!' }} @endif</span>
        </div>
    </div>  

<div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
<label class="control-label col-md-3">  Sub Menu <span class="required"> * </span></label>
<div class="col-md-4"> 
{!! Form::select('parent_id',$allMenu, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Menu']) !!}
<span class="text-danger">{{ $errors->first('parent_id') }}</span>
</div>
</div>

<div class="form-group {{ $errors->first('route_name', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
<label class="control-label col-md-3">  Route Name <span class="required"> * </span></label>
<div class="col-md-4"> 
{!! Form::text('route_name',null, ['class' => 'form-control','data-required'=>1])  !!} 

<span class="help-block" style="color:red">{{ $errors->first('route_name', ':message') }}   </span>
</div>
</div> 



<div class="form-group {{ $errors->first('action', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
<label class="control-label col-md-3">  Action <span class="required"> * </span></label>
<div class="col-md-4"> 
{!! Form::text('action',null, ['class' => 'form-control','data-required'=>1])  !!} 

<span class="help-block" style="color:red">{{ $errors->first('action', ':message') }}   </span>
</div>
<div class="col-md-4">e.g: index,create,show </div>
</div>

<div class="form-group {{ $errors->first('url', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
<label class="control-label col-md-3">  URL <span class="required"> * </span></label>
<div class="col-md-4"> 
{!! Form::text('url',null, ['class' => 'form-control','data-required'=>1 ])  !!} 

<span class="help-block" style="color:red">{{ $errors->first('url', ':message') }}   </span>
</div>
<div class="col-md-4">E.g: /admin/user  </div>
</div>

<div class="form-group {{ $errors->first('display_order', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
<label class="control-label col-md-3">  Display sorder <span class="required"> * </span></label>
<div class="col-md-4"> 
{!! Form::number('display_order',null, ['class' => 'form-control','data-required'=>1])  !!} 

<span class="help-block" style="color:red">{{ $errors->first('display_order', ':message') }}   </span>
</div>
</div>



<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
          {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


           <a href="{{route('menu')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div>