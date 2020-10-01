<script type="text/javascript">
    function removeBtn(id)
    {
        document.getElementById(id).remove();
    }
</script>

@if($prizeBreakup->count()>0)
@foreach ($prizeBreakup as $key => $result) 
    <input type="hidden" name="prize_break" value="prize_break">
      
<div class="form-group col-md-12  pull-right priz-breakup" id="remove_{{$result->id}}">
    <input type="hidden" name="prize_break_id[]" value="{{$result->id}}">
    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12 pull-left"> Rank   from </label>
        <div class="col-md-12"> 
            {!! Form::text('rank_from[]',$result->rank_from, ['class' => 'form-control required' ])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class="  col-md-12"> Rank upto</label>
        <div class="col-md-12"> 
            {!! Form::text('rank_upto[]',$result->rank_upto, ['class' => 'form-control required'])  !!} 
             
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12"> Prize amount</label>
        <div class="col-md-12"> 
            {!! Form::text('prize_amount[]',$result->prize_amount, ['class' => 'form-control required'])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div>

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12"> </label>
        <div class="col-md-12"> 
                <button class="btn btn-danger" onclick="removeBtn('remove_{{$result->id}}')">Remove</button>      
        </div>
    </div>

</div>
@endforeach
@else
    
@for($i=1;$i<=$rank_list;$i++)

<input type="hidden" name="prize_break" value="prize_break">
<div class="form-group col-md-12  pull-right priz-breakup">

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12 pull-left"> Rank   from </label>
        <div class="col-md-12"> 
            {!! Form::text('rank_from[]',$i, ['class' => 'form-control required' ])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class="  col-md-12"> Rank upto</label>
        <div class="col-md-12"> 
            {!! Form::text('rank_upto[]',1, ['class' => 'form-control required'])  !!} 
             
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12"> Prize amount</label>
        <div class="col-md-12"> 
            {!! Form::text('prize_amount[]',$i, ['class' => 'form-control required'])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div>  
</div>
@endfor
@endif

@if($rank_list>$prizeBreakup->count() && $prizeBreakup->count())
@for($i=1;$i<=($rank_list-$prizeBreakup->count());$i++)

<input type="hidden" name="prize_break" value="prize_break">
<div class="form-group col-md-12  pull-right priz-breakup" id="remove_{{$i}}">

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12 pull-left"> Rank   from </label>
        <div class="col-md-12"> 
            {!! Form::text('rank_from[]',null, ['class' => 'form-control required' ])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class="  col-md-12"> Rank upto</label>
        <div class="col-md-12"> 
            {!! Form::text('rank_upto[]',null, ['class' => 'form-control required'])  !!} 
             
        </div>
    </div> 

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12"> Prize amount</label>
        <div class="col-md-12"> 
            {!! Form::text('prize_amount[]',null, ['class' => 'form-control required'])  !!} 
            
            <span class="help-block">{{ $errors->first('expected_amount', ':message') }}</span>
        </div>
    </div>  

    <div class="form-group {{ $errors->first('total_spots', ' has-error') }} col-md-3">
        <label class=" col-md-12"> </label>
        <div class="col-md-12"> 
                <button class="btn btn-danger" onclick="removeBtn('remove_{{$i}}')">Remove</button>      
        </div>
    </div>

</div>
@endfor
@endif
