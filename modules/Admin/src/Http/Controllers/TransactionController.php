<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Models\User;
use Modules\Admin\Models\Category;
use Modules\Admin\Models\Product;
use Modules\Admin\Models\Transaction;
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
use Route;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher;
use App\Helpers\FCMHelper;
use App\Helpers\Helper;
use Response;
use App\Models\Wallet;
use App\Models\WalletTransaction;

/**
 * Class AdminController
 */
class TransactionController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
     public function __construct()
    {
        $this->middleware('admin');
        View::share('viewPage', 'transaction');
        View::share('sub_page_title', 'Document');
        View::share('helper', new Helper);
        View::share('heading', 'Withdraw Request');
        View::share('route_url', route('transaction'));
        $this->record_per_page = Config::get('app.record_per_page');
    }
    protected $categories;

    /*
     * Dashboard
     * */

    public function index(WalletTransaction $transaction, Request $request) 
    { 

        $page_title = 'Payment Withdraw';
        $page_action = 'View Transaction'; 
        $msg = null;
        
        $search = Input::get('search'); 
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('name','LIKE',"%$search%")
                            ->get('id')->pluck('id');
        
        $payment_string = ['2'=>'Withdraw Initiated','3'=>'Payment Hold','4'=>'Withdraw amount Refunded','5'=>'Withdraw amount Released'];

        $wt = WalletTransaction::find($request->txt_id);

        if($wt && $request->status>1){
           $wt->payment_type_string =  $payment_string[$request->status];
           $wt->payment_status      =  $payment_string[$request->status];
           $wt->withdraw_status     =  $request->status;

           $user     = User::find($wt->user_id);
           if($user && $request->status==4 && $wt->payment_status!=4){
                $wt->debit_credit_status = '+';

                if(!$wt->refund_id){

                    $Wallet = Wallet::firstOrNew([
                                    'user_id' => $user->id,
                                    'payment_type' => 7
                                ]);

                    $Wallet->amount = $Wallet->amount+$wt->amount;
                    $Wallet->total_withdrawal_amount = $wt->amount;
                    $Wallet->payment_type = 4;
                    $Wallet->payment_type_string = "Refund";
                    $Wallet->user_id = $wt->user_id;
                    $Wallet->validate_user = Hash::make($wt->user_id);
                    $Wallet->save();
                }
                $wt->refund_id = $wt->id;
                $wt->save();
           }
            
           if($user){
                $token = $user->device_id;
                $data = [
                        'action' => 'notify' ,
                        'title' => "$wt->payment_status",
                        'message' => "Hi $user->name, Your $wt->payment_status"
                    ];
                $this->sendNotification($token, $data);
                $wt->save();
           }
            
        }

        if ((isset($search) && !empty($search))) { 
               
            $transaction = $transaction->where(function ($query) use ($search,$user) {
                if (!empty($search) && !empty($user)) {
                   $query->whereIn('user_id', $user);
                }
            })
            ->select("*",
                        \DB::raw('(CASE 
                        WHEN withdraw_status = 1 THEN "New Request"
                        WHEN withdraw_status = 2 THEN "Initiated"
                        WHEN withdraw_status = 3 THEN "Payment Hold"
                        WHEN withdraw_status = 4 THEN "Payment Refunded"
                        WHEN withdraw_status = 5 THEN "Payment Released"
                        ELSE "New Request" 
                        END) AS withdraw_status'))
            ->orderBy('id','desc')->Paginate($this->record_per_page);
            $transaction->transform(function($item, $Key){
                            $item->withdraw_amount = WalletTransaction::where('withdraw_status',1)
                                ->where('payment_type',5)
                                ->where('user_id',$item->user_id)
                                ->sum('amount');

                            $item->total_balance = round(Wallet::whereIn('payment_type',[2,3,4])
                                ->where('user_id',$item->user_id)
                                ->sum('amount'),2);

                            $user = User::find($item->user_id);
                            
                            if($user){
                                $item->name     =  $user->name;    
                                $item->email    =  $user->email;
                                $item->user_id    =  $user->id;      
                            }else{
                                $item->name     =  "";    
                                $item->email    =  "";
                                $item->user_id    =  "";
                            }
                            return $item;    
                        });

                        
        } else {   

            $transaction = WalletTransaction::where('payment_type',5)
                        ->orderBy('id','desc')
                        ->select("*",
                        \DB::raw('(CASE 
                        WHEN withdraw_status = 1 THEN "New Request"
                        WHEN withdraw_status = 2 THEN "Initiated"
                        WHEN withdraw_status = 3 THEN "Payment Hold"
                        WHEN withdraw_status = 4 THEN "Payment Refund"
                        WHEN withdraw_status = 5 THEN "Payment Released"
                        ELSE "New Request" 
                        END) AS withdraw_status'))
                        ->whereIn('withdraw_status',[1,2,3,5])
                        ->where('withdraw_status','!=',0)
                        ->orderBy('withdraw_status','ASC')
                        ->Paginate($this->record_per_page);
                        
                        $transaction->transform(function($item, $Key){
                            $item->withdraw_amount = WalletTransaction::where('withdraw_status',1)
                                ->where('payment_type',5)
                                ->where('user_id',$item->user_id)
                                ->sum('amount');

                            $item->total_balance = round(Wallet::whereIn('payment_type',[2,3,4])
                                ->where('user_id',$item->user_id)
                                ->sum('amount'),2);

                            $user = User::find($item->user_id);
                            
                            if($user){
                                $item->name     =  $user->name;    
                                $item->email    =  $user->email;
                                $item->user_id    =  $user->id;      
                            }else{
                                $item->name     =  "";    
                                $item->email    =  "";
                                $item->user_id    =  "";
                            }
                            return $item;    
                        });

        }
        //return $transaction;
        return view('packages::payments.index', compact('transaction', 'page_title', 'page_action','msg'));
   
    }

    /*
     * create  method
     * */

    public function create(Transaction $product) 
    {
        $page_title = 'Transaction';
        $page_action = 'Create Transaction';
        $sub_category_name  = Product::all();
        $category   = Category::all();
        $cat = [];
        foreach ($category as $key => $value) {
             $cat[$value->category_name][$value->id] =  $value->sub_category_name;
        } 

         $categories =  Category::attr(['name' => 'product_category','class'=>'form-control form-cascade-control input-small'])
                        ->selected([1])
                        ->renderAsDropdown(); 
        return view('packages::product.create', compact('categories','cat','category','product','sub_category_name', 'page_title', 'page_action'));
     }

    /*
     * Save Group method
     * */

    public function store(Request $request, WalletTransaction $transaction) 
    {
        $wt = WalletTransaction::find($request->payment_id);   
        
        if($wt){
            $user     = User::find($wt->user_id??285);
            $adminUser  = User::find(env('DEFAULT_USER_ID'));
            
            $registatoin_ids=array();

            $token =  $user->device_id;

            $data = [
                        'action' => 'notify' ,
                        'title' => "Amount Withdrawal Released ",
                        'message' => "Hi $user->name, Your withdraw amount ₹ $wt->amount successfully sent"
                    ];

            if($user){        
                $wt->payment_type_string =  'Withdraw amount Released';
                $wt->payment_status      =  'Withdraw amount Released';
                $wt->withdraw_status     =  5;
                $wt->save();
                $this->sendNotification($token, $data);
            }
        }

        return Redirect::to(route('payments'))
                            ->with('flash_alert_notice', 'Payment Released');
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
     * Edit Group method
     * @param 
     * object : $category
     * */

    public function edit(Transaction $transaction) {

        $page_title = 'Transaction';
        $page_action = 'Show Transaction'; 
        

        return view('packages::product.edit', compact( 'categories','product', 'page_title', 'page_action'));
    }

    public function update(ProductRequest $request, Transaction $transaction) 
    {
           
         
        return Redirect::to(route('transaction'))
                        ->with('flash_alert_notice', 'Transaction was  successfully updated !');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy(Transaction $Transaction) {
        
        Transaction::where('id',$product->id)->delete();

        return Redirect::to(route('transaction'))
                        ->with('flash_alert_notice', 'Transaction was successfully deleted!');
    }

    public function show(Product $product) {
        
    }

}
