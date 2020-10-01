 

<div class="form-body">
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Please fill the required field! </div>
  <!--   <div class="alert alert-success display-hide">
        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
-->
        <div class="form-group {{ $errors->first('is_free', ' has-error') }}">
            <label class="control-label col-md-3">Free Contest Type</label>
            <div class="col-md-4"> 
                <select name="is_free" class="form-control">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
                
                <span class="help-block">{{ $errors->first('is_free', ':message') }}</span>
            </div>
        </div>
        @if($match)  

                 <div class="form-group {{ $errors->first('match_id', ' has-error') }}">
            <label class="control-label col-md-3">Match ID </label>
            <div class="col-md-4"> 
                {!! Form::text('match_id',$match, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('match_id', ':message') }}</span>
            </div>
            Use Match id when you want change in specific match!!.
        </div>
        @else
        <div class="form-group {{ $errors->first('match_id', ' has-error') }}">
            <label class="control-label col-md-3">Match ID </label>
            <div class="col-md-4"> 
                {!! Form::text('match_id',$match, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('match_id', ':message') }}</span>
            </div>
        </div> 
        @endif


        <div class="form-group {{ $errors->first('contest_type', ' has-error') }}">
            <label class="control-label col-md-3">Contest type <span class="required"> * </span></label>
            <div class="col-md-4"> 
                
                 {{ Form::select('contest_type',$contest_type, isset($defaultContest->contest_type)?$defaultContest->contest_type:'', ['class' => 'form-control']) }}

                
                <span class="help-block">{{ $errors->first('contest_type', ':message') }}</span>
            </div>
        </div> 


         <div class="form-group {{ $errors->first('entry_fees', ' has-error') }}">
            <label class="control-label col-md-3">Entry fees </label>
            <div class="col-md-4"> 
                {!! Form::text('entry_fees',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('entry_fees', ':message') }}</span>
            </div>
        </div> 
         <div class="form-group {{ $errors->first('total_spots', ' has-error') }}">
            <label class="control-label col-md-3">Total spots </label>
            <div class="col-md-4"> 
                {!! Form::text('total_spots',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('total_spots', ':message') }}</span>
            </div>
        </div> 


        <div class="form-group {{ $errors->first('prize_percentage', ' has-error') }}">
            <label class="control-label col-md-3">Total Winner </label>
            <div class="col-md-4">
                {!! Form::text('prize_percentage',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('prize_percentage', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('first_prize', ' has-error') }}">
            <label class="control-label col-md-3">First Prize </label>
            <div class="col-md-4"> 
                {!! Form::text('first_prize',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('first_prize', ':message') }}</span>
            </div>
        </div> 

        <div class="form-group {{ $errors->first('winner_percentage', ' has-error') }}">
            <label class="control-label col-md-3">Winner percentage</label>
            <div class="col-md-4">
                {!! Form::text('winner_percentage',null, ['class' => 'form-control'])  !!} 
                
                <span class="help-block">{{ $errors->first('winner_percentage', ':message') }}</span>
            </div>
        </div> 
           

       <div class="form-group {{ $errors->first('cancellation', ' has-error') }}">
            <label class="control-label col-md-3">Cancellation</label>

            <div class="col-md-4"> 
                <select name="cancellation" class="form-control">
                    <option value="0" >False</option>
                    <option value="1" {{$defaultContest->cancellation?'selected':''}}>True</option>
                </select>
                
                <span class="help-block">{{ $errors->first('cancellation', ':message') }}</span>
            </div>

        </div>

        <div class="form-group {{ $errors->first('total_winning_prize', ' has-error') }}">
            <label class="control-label col-md-3">Total Winning Prize</label>
            <div class="col-md-4"> 
                {!! Form::text('total_winning_prize',null, ['class' => 'form-control'])  !!} 
                <span class="help-block">{{ $errors->first('total_winning_prize', ':message') }}</span>
            </div>
        </div>


        <div class="form-group {{ $errors->first('usable_bonus', ' has-error') }}">
            <label class="control-label col-md-3">Usable Bonus %</label>
            <div class="col-md-4"> 
                {!! Form::text('usable_bonus',$defaultContest->usable_bonus??10, ['class' => 'form-control','placeholder'=>'10'])  !!} 
                <span class="help-block">{{ $errors->first('usable_bonus', ':message') }}</span>
            </div>
        </div>

        <div class="form-group {{ $errors->first('bonus_contest', ' has-error') }}">
            <label class="control-label col-md-3">Bonus Contest</label>
            <div class="col-md-4"> 
                <select name="bonus_contest" class="form-control">
                    <option value="0" >No</option>
                    <option value="1" {{$defaultContest->bonus_contest?'selected':''}}>Yes</option>
                </select>
                
                <span class="help-block">{{ $errors->first('bonus_contest', ':message') }}</span>
            </div>

        </div>

    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
              {!! Form::submit(' Save ', ['class'=>'btn  btn-primary text-white','id'=>'saveBtn']) !!}


               <a href="{{route('defaultContest')}}">
    {!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
            </div>
        </div>
    </div>
