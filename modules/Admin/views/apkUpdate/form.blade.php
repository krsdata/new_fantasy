<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>  
 	<div class="form-group {{ $errors->first('title', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
        <label class="control-label col-md-3"> Title <span class="required"> * </span></label>
        <div class="col-md-4"> 
            {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!} 
            
            <span class="help-block" style="color:red">{{ $errors->first('title', ':message') }} @if(session('field_errors')) {{ 'The  apkUpdate name already been available!' }} @endif</span>
        </div>
    </div> 

    <div class="form-group {{ $errors->first('title', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
        <label class="control-label col-md-3"> Version Code <span class="required"> * </span></label>
        <div class="col-md-4"> 
            {!! Form::text('version_code',null, ['class' => 'form-control','data-required'=>1])  !!} 
            example : 3.1
            <span class="help-block" style="color:red">{{ $errors->first('version_code', ':message') }} @if(session('field_errors')) {{ 'The  version_code  already been available!' }} @endif</span>
        </div>
    </div> 
    <div class="form-group  {{ $errors->first('photo', ' has-error') }}">
        <label class="control-label col-md-3"> Upload Apk<span class="required"> * </span></label>
        <div class="col-md-9">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                    <img src=" {{ $url ?? 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'}}" alt=""> </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;"></div>
                    <div>
                    <span class="btn default btn-file">
                        <span class="fileinput-new"> Select APK </span>
                        <span class="fileinput-exists"> Change </span>
                       
                        {!! Form::file('apk',null,['class' => 'form-control form-cascade-control input-small'])  !!}
 
                          <span class="help-block" style="color:#e73d4a">{{ $errors->first('apk', ':message') }}</span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                </div>
            </div>
           
        </div>
    </div> 
   
    <div class="form-group {{ $errors->first('description', ' has-error') }}">
        <label class="control-label col-md-3">Message<span class="required"> </span></label>
        <div class="col-md-4"> 
            {!! Form::textarea('message',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
            
            <span class="help-block">{{ $errors->first('message', ':message') }}</span>
        </div>
    </div> 

     <div class="form-group {{ $errors->first('description', ' has-error') }}">
        <label class="control-label col-md-3">Release notes<span class="required"> </span></label>
        <div class="col-md-4"> 
            {!! Form::textarea('release_notes',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
            
            <span class="help-block">{{ $errors->first('release_notes', ':message') }}</span>
        </div>
    </div> 
    
    
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
          {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


           <a href="{{route('apkUpdate')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div> 
    