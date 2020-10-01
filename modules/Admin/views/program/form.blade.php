 

<div class="form-body col-md-6">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! 
    </div> 
 
    <div class="form-group {{ $errors->first('campaign_name', ' has-error') }}">
            <label class="control-label col-md-4">Campaign Name <span class="required"> * </span></label>
            <div class="col-md-7"> 
                {!! Form::text('campaign_name',null, ['class' => 'form-control','data-required'=>1])  !!} 
                
                <span class="help-block">{{ $errors->first('campaign_name', ':message') }}</span>
            </div>
    </div>  
       


        <div class="form-group {{ $errors->first('start_date', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">Start Date 
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 

                  {!! Form::text('start_date',null, ['id'=>'startdate','class' => 'form-control end_date','data-required'=>1,"size"=>"16","data-date-format"=>"dd-mm-yyyy","data-date-start-date"=>"+0d" ])  !!} 
                
                <span class="help-block">{{ $errors->first('start_date', ':message') }}</span>
            </div> 
        </div>

         <div class="form-group {{ $errors->first('end_date', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">End Date 
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 
                {!! Form::text('end_date',null, ['id'=>'enddate','class' => 'form-control end_date','data-required'=>1,"size"=>"16","data-date-format"=>"dd-mm-yyyy","data-date-start-date"=>"+0d" ])  !!} 


                
                <span class="help-block">{{ $errors->first('end_date', ':message') }}</span>
            </div> 
        </div>

        <div class="form-group {{ $errors->first('reward_type', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">Reward Type
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 

                {{ Form::select('reward_type',$status, isset($program->reward_type)?$program->reward_type:'', ['class' => 'form-control']) }}
                <span class="help-block">{{ $errors->first('reward_type', ':message') }}</span>
            </div> 
        </div>

         <div class="form-group {{ $errors->first('amount', ' has-error') }}">
            <label class="control-label col-md-4">Fixed/Percentage Amt. <span class="required"> * </span></label>
            <div class="col-md-7"> 
                {!! Form::text('amount',null, ['class' => 'form-control','data-required'=>1,'onkeypress'=>'return isNumberKey(event)'])  !!} 
                 
                <span class="help-block">{{ $errors->first('amount', ':message') }}</span>
            </div>
    </div>


</div>
<div class="form-body col-md-6">


    <div class="form-group {{ $errors->first('promotion_type', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">Promotion Type
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 

                {{ Form::select('promotion_type' , ['0'=>'Select Type','1'=>'Referral',2=>'Bonus'], $program->promotion_type,['class' => 'form-control']) }}
                <span class="help-block">{{ $errors->first('promotion_type', ':message') }}</span>
            </div> 
        </div>

     <div class="form-group {{ $errors->first('trigger_condition', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">Trigger Condition
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 

                {{ Form::select('trigger_condition', [0=>'Select Condition','1'=>'Sign Up',2=>'First Transaction'], $program->trigger_condition,['class' => 'form-control']) }}
                <span class="help-block">{{ $errors->first('trigger_condition', ':message') }}</span>
            </div> 
        </div>

     <div class="form-group {{ $errors->first('status', ' has-error') }}  @if(session('field_errors')) {{ 'has-group' }} @endif">
            <label class="col-md-4 control-label">Promotion Status
                <span class="required"> * </span>
            </label>
            <div class="col-md-7"> 

                {{ Form::select('status', [0=>'Select Status','1'=>'Active',2=>'Planned',3=>'Draft'], $program->status,['class' => 'form-control']) }}
                <span class="help-block">{{ $errors->first('status', ':message') }}</span>
            </div> 
        </div>
 
         <div class="form-group {{ $errors->first('customer_type', ' has-error') }}">
            <label class="control-label col-md-4">Customer type </label>
            <div class="col-md-7"> 
                {{ Form::select('customer_type', [0=>'Select Customer Type','1'=>'Public'], $program->customer_type,['class' => 'form-control']) }}
                <span class="help-block">{{ $errors->first('customer_type', ':message') }}</span> 
            </div>
        </div> 
          <div class="form-group {{ $errors->first('description', ' has-error') }}">
            <label class="control-label col-md-4">Description<span class="required"> </span></label>
            <div class="col-md-7"> 
                {!! Form::textarea('description',null, ['class' => 'form-control','data-required'=>1,'rows'=>3,'cols'=>5])  !!} 
                
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div> 


    
    
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
          {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


           <a href="{{route('program')}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
        </div>
    </div>
</div>




<div class="form-body">

  <script type="text/javascript">
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </script>



</div> 

