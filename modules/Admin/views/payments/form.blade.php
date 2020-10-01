 <div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>

 	<div class="form-group {{ $errors->first('title', ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
        <label class="control-label col-md-3"> Title <span class="required"> * </span></label>
        <div class="col-md-4"> 
            {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!} 
            
            <span class="help-block" style="color:red">{{ $errors->first('title', ':message') }} @if(session('field_errors')) {{ 'The title name already been taken!' }} @endif</span>
        </div>
    </div>  

<div class="form-group  {{ $errors->first('image_name', ' has-error') }}">
    <label class="control-label col-md-3"> Image Upload <span class="required"> * </span></label>
        <div class="col-md-9">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                    <img src=" {{ $url ?? 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'}}" alt=""> </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;"></div>
                <div>
                    <span class="btn default btn-file">
                    <span class="fileinput-new"> Select image </span>
                    <span class="fileinput-exists"> Change </span>
                           
                        {!! Form::file('image_name',null,['class' => 'form-control form-cascade-control input-small'])  !!} 

                    </span>
                    <span class="help-block" style="color:#e73d4a">
                        {{ $errors->first('image_name', ':message') }}
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="help-block">NOTE!</span> Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead. </div>
        </div>
    </div>
     <div class="form-group {{ $errors->first('editor_id', 'has-error') }}">
            <label class="control-label col-md-3">Select Editor  </label>
            <div class="col-md-4">  
               {{ Form::select('editor_id',$editor, $editorPost->editor_id, ['class' => 'form-control']) }}
                
                <span class="help-block">{{ $errors->first('editor_id', ':message') }}</span>
            </div>
    </div>

    <div class="form-group {{ $errors->first('category_name', 'has-error') }}">
            <label class="control-label col-md-3">Select category  </label>
            <div class="col-md-4">  
               {{ Form::select('category_name',$category_name, $editorPost->category_name, ['class' => 'form-control']) }}
                
                <span class="help-block">{{ $errors->first('category_name', ':message') }}</span>
            </div>
    </div> 

    <div class="form-group {{ $errors->first('software_editor', 'has-error') }}">
            <label class="control-label col-md-3">Software Editor Used  </label>
            <div class="col-md-4">  
               {{ Form::select('software_editor',$software_editor, $editorPost->software_editor, ['class' => 'form-control']) }}
                
                <span class="help-block">{{ $errors->first('software_editor', ':message') }}</span>
            </div>
    </div> 

    
    <div class="form-group {{ $errors->first('description', ' has-error') }}">
        <label class="control-label col-md-3">Description<span class="required"> </span></label>
        <div class="col-md-4"> 
            {!! Form::textarea('description',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
            
            <span class="help-block">{{ $errors->first('description', ':message') }}</span>
        </div>
    </div> 
    
    
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
          {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


           <a href="{{route('editorPost')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div>
