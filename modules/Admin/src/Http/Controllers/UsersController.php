<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\UserRequest;
use Modules\Admin\Models\User; 
use Input;
use Validator;
use Auth;
use Paginate;
use Grids;
use HTML;
use Form;
use Hash;
use View;
use URL;
use Lang;
use Session;
use DB;
use Route;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher; 
use App\Helpers\Helper;
use Modules\Admin\Models\Roles;

use Modules\Admin\Models\Document;
use Modules\Admin\Models\BankAccounts; 
 

/**
 * Class AdminController
 */
class UsersController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(Request $request) {
        $this->middleware('admin');
        View::share('viewPage', 'Users');
        View::share('helper',new Helper);
        View::share('heading','Users');
        View::share('route_url',route('user'));
        $this->record_per_page = Config::get('app.record_per_page');
    }

    protected $users;

    /*
     * Dashboard
     * */

    public function index(User $user, Request $request) 
    {   
        $page_title = 'Users';
        $page_action = 'View Users'; 
        if ($request->ajax()) {
            $id = $request->get('id');
            $status = $request->get('status');
            $user = User::find($id);
            $s = ($status == 1) ? ($status = 0) : ($status = 1);
            $user->status = $s;
            $user->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        $role_type = Input::get('role_type'); 
        $mobile_number = $request->get('mobile_number'); 
        if ((isset($search) && !empty($search)) OR  (isset($status) && !empty($status)) OR !empty($role_type) OR !empty($mobile_number)) {

            $search = isset($search) ? Input::get('search') : '';
               
            $users = User::where(function($query) use($mobile_number,$search,$status,$role_type) {

                        if (!empty($search)) {
                            $query->Where('name', 'LIKE', "%$search%")
                                    ->OrWhere('email', 'LIKE', "%$search%")
                                    ->OrWhere('referal_code', 'LIKE', "%$search%")
                                    ->OrWhere('reference_code', 'LIKE', "%$search%")
                                    ->OrWhere('user_name',$search)
                                    ->OrWhere('team_name', $search)
                                    ->OrWhere('id', $search);
                        }
                        if (!empty($status)) {
                            $status =  ($status=='active')?1:0;
                            $query->Where('status',$status);
                        }
                        if (!empty($mobile_number)) {
                            $query->where('mobile_number', 'LIKE', "%$mobile_number%");
                        }
                        if ($role_type) {
                            $query->Where('role_type',$role_type);
                        }
                    })->orderBy('id','asc')->Paginate($this->record_per_page);
            $users->transform(function($item,$key){
                $wallet_amount = \DB::table('wallets')->where('user_id',$item->id)->sum('amount');
                $item->balance = $wallet_amount;
                //SELECT payment_type, payment_type_string, sum(amount) FROM `wallet_transactions` WHERE `user_id` = 686 GROUP by payment_type

                $ref_count = \DB::table('referral_codes')->where('refer_by',$item->id)->count();
                $item->ref_count = $ref_count;
                $account_details = \DB::table('wallet_transactions')
                                    ->where('user_id',$item->id)
                                    ->get()
                                    ->groupBy('payment_type_string')
                                    ->transform(function($item,$key){
                                        
                                        foreach ($item as $key => $value) {
                                            $sum[] = $value->amount;
                                            $payment_type_string = $value->payment_type_string;
                                        }
                                        $item->$payment_type_string = array_sum($sum);
                                        return $item;
                                    });
                $amount['Bonus'] = 0;                    
                foreach ($account_details as $key => $value) {
                    $amount[$key] = $value->$key;
                }
                $ref_deposit = \DB::table('wallet_transactions')
                            ->where('payment_type',3)
                            ->where('user_id',$item->id)
                            ->sum('amount');
                $item->ref_deposit = $ref_deposit;
                
                $ref_uid = \DB::table('users')->where('reference_code',$item->referal_code)->pluck('id')->toArray();
                
                if($ref_uid){
                    $reference_deposit = \DB::table('wallet_transactions')
                            ->where('payment_type',3)
                            ->whereIn('user_id',$ref_uid)
                            ->sum('amount');
                    $item->reference_deposit = $reference_deposit;    
                }
                

                $item->amount = $amount; 
                return $item;
            });
        } else {
            $users = User::orderBy('id','desc')->Paginate($this->record_per_page);
            $users->transform(function($item,$key){
                $wallet_amount = \DB::table('wallets')->where('user_id',$item->id)->whereIn('payment_type',[1,2,3,4])->sum('amount');
                $item->balance = $wallet_amount;
                
                $ref_count = \DB::table('referral_codes')
                            ->where('refer_by',$item->id)
                            ->count();
                $item->ref_count = $ref_count;


                $ref_deposit = \DB::table('wallet_transactions')
                            ->where('payment_type',3)
                            ->where('user_id',$item->id)
                            ->sum('amount');
                $item->ref_deposit = $ref_deposit;

                $account_details = \DB::table('wallet_transactions')
                                    ->where('user_id',$item->id)
                                    ->get()
                                    ->groupBy('payment_type_string')
                                    ->transform(function($item,$key){
                                        
                                        foreach ($item as $key => $value) {
                                            $sum[] = $value->amount;
                                            $payment_type_string = $value->payment_type_string;
                                        }
                                        $item->$payment_type_string = array_sum($sum);
                                        return $item;
                                    });
                $amount['Bonus'] = 0;                    
                foreach ($account_details as $key => $value) {
                    $amount[$key] = $value->$key;
                }   
                $item->amount = $amount; 

                $ref_uid = \DB::table('users')->where('reference_code',$item->referal_code)->pluck('id')->toArray();
                
                if($ref_uid){
                    $reference_deposit = \DB::table('wallet_transactions')
                            ->where('payment_type',3)
                            ->whereIn('user_id',$ref_uid)
                            ->sum('amount');
                    $item->reference_deposit = $reference_deposit;    
                }

                return $item;
            });
            
        } 
       // return $users;
        $roles = Roles::all();
        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return view('packages::users.user.index', compact('js_file','roles','status','users', 'page_title', 'page_action','roles','role_type'));
    }

    /*
     * create Group method
     * */

    public function create(User $user) 
    {
        $page_title = 'Editor';
        $page_action = 'Create Editor';
        $roles = Roles::all();
        $role_id = null;
        $js_file = ['common.js','bootbox.js','formValidate.js'];

        return view('packages::users.create', compact('js_file','role_id','roles', 'user', 'page_title', 'page_action'));
    }

    public function sendNotification($token, $data){
     
        $serverLKey = 'AIzaSyAFIO8uE_q7vdcmymsxwmXf-olotQmOCgE';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

       $extraNotificationData = $data;

       if(is_array($token)){
            $fcmNotification = [
               'registration_ids' => $token, //multple token array
              // 'to' => $token, //single token
               //'notification' => $notification,
               'data' => $extraNotificationData
            ];
       }else{
            $fcmNotification = [
           //'registration_ids' => $tokenList, //multple token array
           'to' => $token, //single token
           //'notification' => $notification,
           'data' => $extraNotificationData
        ];
        }
       

       $headers = [
           'Authorization: key='.$serverLKey,
           'Content-Type: application/json'
       ];


       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $fcmUrl);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
       $result = curl_exec($ch);
       //echo "result".$result;
       //die;
       curl_close($ch);
       return true;
    }
    /*
     * Save Group method
     * */

    public function store(Request $request, User $user) {

        $user = User::where(['email'=>$request->email])->first();
        if($user  && $request->notification && $request->message_type=="notify")
        {
            $title = $request->title;
            $message = $request->message;
            $device_id = $user->device_id;
            $data = [
                    'action' => 'notify' ,
                    'title' => $title ,
                    'message' => $message
                ]; 
            $this->sendNotification($device_id, $data);
            return Redirect::to(route('user'));
        }

        $user->fill(Input::all());
        $user->password = Hash::make($request->get('password'));
        $action = $request->get('submit');
        if($action=='avtar'){ 
            if ($request->file('profile_image')) {
                $profile_image = User::createImage($request,'profile_image');
                $request->merge(['profilePic'=> $profile_image]);
               $user->profile_image = $request->get('profilePic'); 
            }
           
        }
        $user->save();
        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return Redirect::to(route('user'))
                            ->with('flash_alert_notice', 'New record successfully created.');
        }

    /*
     * Edit Group method
     * @param 
     * object : $user
     * */

    public function edit($id) {
        $user = User::find($id);
        $page_title = 'Editor';
        $page_action = 'Show Editor';
        $role_id = $user->role_type;
        $roles = Roles::all();

        $match_id = \DB::table('join_contests')->where('user_id',$id)
                    ->groupBy('match_id')->pluck('match_id')->count();

        $contest_id = \DB::table('join_contests')
                    ->where('user_id',$id)
                    ->groupBy('contest_id')->pluck('contest_id')->count();
        $win = \DB::table('join_contests')
                    ->where('user_id',$id)
                    ->where('winning_amount','>',0)->count();
        $referral =  \DB::table('referral_codes')
                    ->where('refer_by',$id)
                    ->count();

         $total_balance =  \DB::table('wallets')
                    ->where('user_id',$id)
                    ->sum('amount');

        $deposit =  \DB::table('wallets')
                    ->where('user_id',$id)
                    ->where('payment_type',3)
                    ->sum('amount');

        $prize =  \DB::table('wallets')
                    ->where('user_id',$id)
                    ->where('payment_type',4)
                    ->sum('amount'); 

        $document =    Document::where('user_id', $id)
                        ->where(function($q){
                            $q->where('doc_type','pancard');
                            $q->orWhere('doc_type','adharcard');
                        })
                        ->first();

        $bank =    BankAccounts::where('user_id', $id)
                        ->first();

        $paytm =    Document::where('user_id', $id)
                        ->where(function($q){
                            $q->where('doc_type','paytm');
                        })
                        ->first();

        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return view('packages::users.edit', compact('js_file','role_id','roles','user', 'page_title', 'page_action','match_id','contest_id','win','referral','deposit','prize','document','paytm','bank'));
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $action = $request->get('submit');
        $user->fill(Input::all());
        $user->role_type= $request->get('role_type');
        $user->save();   

        if($action=='avtar'){ 
            if ($request->file('profile_image')) {
                $profile_image = User::createImage($request,'profile_image');
                $request->merge(['profilePic'=> $profile_image]);
               $user->profile_image = $request->get('profilePic'); 
            } 
        } 

        if($request->action=='document'){
            if ($request->file('document')) {
                $document_url = User::uploadDocs($request,'document');
                $request->merge(['document_url'=> $document_url]); 
            }
            if($request->account_number){
                $bankAcc = BankAccounts::firstOrNew([
                'user_id' => $request->user_id
                ]);
                $bankAcc->bank_name = $request->bank_name;
                $bankAcc->account_name = $request->account_name;
                $bankAcc->account_number = $request->account_number;
                $bankAcc->ifsc_code = $request->ifsc_code;
                $bankAcc->account_type = $request->account_type;
                $bankAcc->user_id = $request->user_id;
                $bankAcc->save(); 
            }
            //doc_url_front
            $docs = Document::firstOrNew([
                'user_id' => $request->user_id,
                'doc_type' => $request->doc_type
                ]);

            $docs->user_id      = $request->user_id;
            $docs->doc_type     = $request->doc_type;
            $docs->doc_number   = $request->doc_number;
            $docs->doc_name     = $request->account_name;
            if($request->document_url){
                $docs->doc_url_front = $request->document_url;    
            }
            $docs->status       = 2;
            $docs->save();

            if($request->paytm){
                $paytm = Document::firstOrNew([
                'user_id' => $request->user_id,
                'doc_type' => 'paytm'
                ]);
                $paytm->user_id      = $request->user_id;
                $paytm->doc_type     = 'paytm';
                $paytm->doc_number   = $request->paytm;
                $paytm->doc_name     = $request->account_name;
                $paytm->status       = 2;
                $paytm->save();
            }
        } 

        $validator_email = User::where('email',$request->get('email'))
                            ->where('id','!=',$user->id)->first();
        if($validator_email) {
            if($validator_email->id==$user->id)
            {
                $user->save();
            }else{
                  return  Redirect::back()->withInput()->with(
                    'field_errors','The Email already been taken!'
                 );
                 
            }
        } 
        $Redirect = 'user';
       
        return Redirect::to(route($Redirect))
                        ->with('flash_alert_notice', 'Record successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($id) {
        
        User::where('id',$id)->delete();

        return Redirect::to(route('user'))
                        ->with('flash_alert_notice', 'User  successfully deleted.');
    }

    public function show(User $user) {
        
    }

}
