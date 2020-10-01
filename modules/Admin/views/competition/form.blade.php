 
  
<div class="form-body">
<div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button> Please fill the required field! </div>


    @foreach($tables as $col_name)

    <div class="form-group {{ $errors->first($col_name, ' has-error') }}  @if(session('field_errors')) {{ 'has-error' }} @endif">
        <label class="control-label col-md-3">  {{ str_replace('_',' ', $col_name) }} <span class="required"> * </span></label>
        <div class="col-md-4"> 
            {!! Form::text($col_name,null, ['class' => 'form-control','data-required'=>1,'required'=>true])  !!} 
            @if($col_name=='payment_type')
                Example(Use Either) : 1,2,3,4 <br>
                1=Bonus, 2=Refferal,3=Deposit,4=Withdraw  
            @endif
            @if($col_name=='payment_type_string')
           Example :  Bonus or Refferal or Deposit or Withdrawal   
            @endif
            <span class="help-block" style="color:red">{{ $errors->first($col_name, ':message') }} @if(session('field_errors')) {{ 'The  Title name already been taken!' }} @endif</span>
        </div>
    </div>  
@endforeach
<div class="form-actions">
<div class="row">
    <div class="col-md-offset-3 col-md-9">
      {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


       <a href="{{URL::previous()}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
    </div>
</div>
</div>


