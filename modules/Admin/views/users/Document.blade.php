<div class="tab-pane" id="Document">  
<div class="portlet light bordered">
 <div class="portlet-title">
            <div class="caption">
                <i class="icon-social-dribbble font-green"></i>
                <span class="caption-subject font-green bold uppercase">Documents/Bank Details
                </span>
            </div> 
        </div>
            <input type="hidden" name="action" value="document">
            <div class="form-group">
                    <label for="multiple" class="control-label">User ID</label>
                     <input type="text" name="user_id" class="form-control" value="{{$user->id}}" readonly="">
                </div>
            <div class="form-group">
                    <label for="multiple" class="control-label">Document Type</label>
                     <select class="form-control" name="doc_type">
                         <option value="pancard" @if(isset($document) && $document->doc_type=='pancard') selected @endif>PAN CARD</option>
                         <option value="adharcard" @if($document) &&  $document->doc_type=='adharcard') selected @endif>Adhar CARD</option>
                     </select>
                </div>
                
                <div class="form-group">
                    <label for="multiple" class="control-label">Adhar/PAN Number</label>
                     <input type="text" name="doc_number" class="form-control"  value="{{$document->doc_number??''}}">
                </div>


            <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                 @if(empty($document->doc_url_front))
                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""> 
                        @else
                         <img src="{{$document->doc_url_front??''}}" alt="doc_url_front"> 
                        @endif
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> 
            </div>
            <div>
                <span class="btn default btn-file">
                <span class="fileinput-new"> Select image </span>
                <span class="fileinput-exists"> Change </span>
                       
                {!! Form::file('document',null,['class' => 'form-actionsontrol form-cascade-control input-small'])  !!} 
                </span>
                <span class="help-block" style="color:#e73d4a">{{ $errors->first('document', ':message') }}</span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove    
                    </a>
            </div>
        </div>
            <div class="form-group">
                    <label for="multiple" class="control-label">PayTm Number</label>
                     <input type="" name="paytm" value="{{$paytm->doc_number??''}}" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="multiple" class="control-label">Account Holder Name</label>
                     <input type="text" value="{{$bank->account_name??''}}" name="account_name" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="multiple" class="control-label">Bank Name</label>
                     <input type="text" value="{{$bank->bank_name??''}}" name="bank_name" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="multiple" class="control-label">Bank Account Number</label>
                     <input type="text" value="{{$bank->account_number??''}}" name="account_number" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="multiple" class="control-label">IFSC Code</label>
                     <input type="text" value="{{$bank->ifsc_code??''}}" name="ifsc_code" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="multiple" class="control-label">Account type</label>
                     <input type="text" value="{{$bank->account_name??''}}" name="account_type" class="form-control" >
                </div>

             <div class="form-group">
                
                <div class="clearfix margin-top-10">
                    <span class="label label-danger">NOTE! </span>
                    <span> 
                    <br>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                </div>
            </div>

            <div class="margin-top-10"> 
                 <button type="submit" class="btn green" value="avtar" name="submit"> Save Changes </button>
                <a href="{{url(URL::previous())}}">
{!! Form::button('Back', ['class'=>'btn btn-warning text-white']) !!} </a>
            </div> 
           <!--  {!! Form::close() !!} -->
            </div>
          
</div>