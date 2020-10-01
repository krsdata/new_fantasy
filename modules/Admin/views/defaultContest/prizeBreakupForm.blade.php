 

<div class="form-body col-md-12">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
  <!--   <div class="alert alert-success display-hide">
        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
-->
       


        <div class="form-group {{ $errors->first('contest_type', ' has-error') }} col-md-6">
            <label class="control-label col-md-6">Contest type <span class="required"> * </span></label>
            <div class="col-md-6"> 
                

                 {{ Form::select('contest_type_id',$contest_type, isset($defaultContest->contest_type)?$defaultContest->contest_type:'', ['class' => 'form-control']) }}

                 
            </div>
        </div> 


         <div class="form-group {{ $errors->first('entry_fees', ' has-error') }} col-md-6">
            <label class="control-label col-md-4">Entry fees </label>
            <div class="col-md-6"> 
                {!! Form::text('entry_fees',null, ['class' => 'form-control',])  !!} 
                
                <span class="help-block">{{ $errors->first('entry_fees', ':message') }}</span>
            </div>
        </div> 

         <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-6">
            <label class="control-label col-md-6">Total spots </label>
            <div class="col-md-6"> 
                {!! Form::text('total_spots',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('total_spots', ':message') }}</span>
            </div>
        </div> 
 
        <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-6">
            <label class="control-label col-md-4"> Amount to be collect </label>
            <div class="col-md-6"> 
                {!! Form::text('expected_amount',$expected_amount, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
            </div>
        </div> 
        <div class="prize_break_class">

<form  method="get" action="http://localhost/cricket/admin/defaultContest/1?rank_list=5">
        <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-6">
            <label class="control-label col-md-6">Generate Rank List</label>
            <div class="col-md-6"> 
                {!! Form::number('rank_list',null, ['class' => 'form-control','min'=>1])  !!} 
                
             
            </div>
        </div> 
 
        <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-6 pull-left">
            <label class="control-label col-md-4">  </label>
            <div class="col-md-12"> 
             
             {!! Form::submit('Generate Prize rang List', ['class'=>'btn btn-warning text-white']) !!}  

              {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}
                <a href="{{route('defaultContest')}}">
    {!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
              
            </div>
        </div> 
</form>
<input type="hidden" name="default_contest_id" value="{{$default_contest_id}}">
</div>
<div class="form-group col-md-12">
<hr> 
<p style="text-align: center">Prize  Breakup List</p> <hr>
</div>

        {!!$html!!}
       
 
        
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">

             
            </div>
        </div>
    </div>
