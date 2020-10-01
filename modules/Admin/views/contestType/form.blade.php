 

<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
  <!--   <div class="alert alert-success display-hide">
        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
-->
 
    <div class="form-group {{ $errors->first('contest_type', ' has-error') }}">
            <label class="control-label col-md-3">Contest Type Name <span class="required"> * </span></label>
            <div class="col-md-4"> 
                {!! Form::text('contest_type',null, ['class' => 'form-control','data-required'=>1])  !!} 
                
                <span class="help-block">{{ $errors->first('contest_type', ':message') }}</span>
            </div>
        </div> 

          <div class="form-group {{ $errors->first('description', ' has-error') }}">
            <label class="control-label col-md-3">Description<span class="required"> </span></label>
            <div class="col-md-4"> 
                {!! Form::textarea('description',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
                
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div> 

         <div class="form-group {{ $errors->first('cancellable', ' has-error') }}">
            <label class="control-label col-md-3">Cancellable  </label>
            <div class="col-md-4"> 
                {!! Form::text('cancellable',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('cancellable', ':message') }}</span>
            </div>
        </div> 
         <div class="form-group {{ $errors->first('max_entries', ' has-error') }}">
            <label class="control-label col-md-3">Max Entry </label>
            <div class="col-md-4"> 
                {!! Form::text('max_entries',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('max_entries', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('sort_by', ' has-error') }}">
            <label class="control-label col-md-3">Sort By </label>
            <div class="col-md-4"> 
                {!! Form::text('sort_by',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('sort_by', ':message') }}</span>
            </div>
        </div> 
        

    
    
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
          {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


           <a href="{{route('contestType')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div>




<div class="form-body">





</div> 

