<style type="text/css">
 li.multiselect-all,li.multiselect-item {
    margin-left: 25px;
  }
  ul.multiselect-container > li{
     margin-left: 25px;
  }
</style> 

<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
  <!--   <div class="alert alert-success display-hide">
        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
-->
 
        <div class="form-group {{ $errors->first('title', ' has-error') }}">
            <label class="control-label col-md-3">Page Title <span class="required"> * </span></label>
            <div class="col-md-4"> 
                {!! Form::text('title',null, ['class' => 'form-control','data-required'=>1])  !!}
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div> 

         <div class="form-group{{ $errors->first('page_content', ' has-error') }}">
        <label class="control-label col-md-3">Page Content</label>
        <div class="col-md-8"> 
            {!! Form::textarea('page_content',null, ['class' => 'form-control ckeditor form-cascade-control input-small'])  !!}
            <span class="label label-danger">{{ $errors->first('page_content', ':message') }}</span>
            @if(Session::has('flash_alert_notice')) 
            <span class="label label-danger">
                {{ Session::get('flash_alert_notice') }} 
            </span>@endif
        </div>
    </div> 

        <div class="form-group {{ $errors->first('slug', 'has-error') }}">
            <label class="control-label col-md-3">Slug  </label>
            <div class="col-md-6">  
               {!! Form::text('slug',null, ['class' => 'form-control'])  !!} 
                <span class="help-block">{{ $errors->first('slug', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('url', 'has-error') }}">
            <label class="control-label col-md-3">Custom Url  </label>
            <div class="col-md-6"> 
               {!! Form::text('url',null, ['class' => 'form-control'])  !!} 
                <span class="help-block">{{ $errors->first('url', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('meta_title', 'has-error') }}">
            <label class="control-label col-md-3">Meta Title  </label>
            <div class="col-md-6">  
               {!! Form::text('meta_title',null, ['class' => 'form-control'])  !!} 
                <span class="help-block">{{ $errors->first('meta_title', ':message') }}</span>
            </div>
        </div> 


        <div class="form-group {{ $errors->first('meta_key', 'has-error') }}">
            <label class="control-label col-md-3">Meta Key  </label>
            <div class="col-md-6"> 
                {!! Form::text('meta_key',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('meta_key', ':message') }}</span>
            </div>
        </div> 


        <div class="form-group {{ $errors->first('meta_description', 'has-error') }}">
            <label class="control-label col-md-3">Meta Description  </label>
            <div class="col-md-6">  
                {!! Form::textarea('meta_description',null, ['class' => 'form-control ckeditor form-cascade-control input-small'])  !!}
                <span class="help-block">{{ $errors->first('meta_description', ':message') }}</span>
            </div>
        </div> 


         <div class="form-group {{ $errors->first('images', ' has-error') }}">
            <label class="control-label col-md-3">Upload Image  </label>
            <div class="col-md-6">
                {!! Form::file('images',null,['class' => 'form-control'])  !!}
             <br>
              @if(isset($page->images))
              <img src="{!! Url::to('storage/pages/'.$page->images) !!}" width="150px">
              @endif                                   
            <span class="label label-danger">{{ $errors->first('images', ':message') }}</span>
            @if(Session::has('flash_alert_notice')) 
            <span class="label label-danger">

                {{ Session::get('flash_alert_notice') }} 

            </span>@endif

            </div>
        </div>  
    
    
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
         <!--  {!! Form::button(' Save ', ['class'=>'btn save  btn-primary text-white','id'=>'saveBtn']) !!} -->
          {!! Form::submit('Save', ['class'=>'btn save btn-primary text-white','id'=>'saveBtn']) !!} 

           <a href="{{route('content')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div>

 