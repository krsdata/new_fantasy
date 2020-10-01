<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Models\User; 
use Modules\Admin\Models\PrizeDistribution;
use Modules\Admin\Models\Wallets;
use Modules\Admin\Models\Transaction;
use Modules\Admin\Models\WalletsTrasaction;
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
use Modules\Admin\Helpers\Helper as Helper; 
use App\Helpers\FCMHelper;
use Response;

/**
 * Class AdminController
 */
class TransactionHistoryController extends Controller {
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
        View::share('viewPage', 'Payment');
        View::share('sub_page_title', 'Payment History');
        View::share('helper', new Helper);
        View::share('heading', 'Payment History');
        View::share('route_url', route('paymentsHistory'));
        $this->record_per_page = Config::get('app.record_per_page');
    }
    protected $categories;

    /*
     * Dashboard
     * */

    public function index(WalletsTrasaction $transaction, Request $request) 
    {  
        $page_title = 'Payment';
        $page_action = 'Payment History'; 
        $msg = null;
        
        if ($request->status && $request->txt_id) {
            
            $txt = WalletsTrasaction::find($request->txt_id);
            $txt->status = $request->status;
            $txt->save();
            $msg = "Payments status updated.";

           // $user       = User::find($txt->user_id);
            $editor     = User::find($txt->editor_id);
            $adminUser  = User::find(env('DEFAULT_USER_ID'));

            $registatoin_ids = array();
            if($request->status==2){ 
                $title = "Payment Hold";
                $msg2 = "Your request payment  ".$txt->payable_amount." is on hold";
            }
            elseif($request->status==3){
                $title = "Payment Failed";
                $msg2 = "Your request payment  ".$txt->payable_amount." is on Failed";
            }

            elseif($request->status==4){
                $title = "Payment Rejected";
                $msg2 = "Your request payment  ".$txt->payable_amount." is on Rejected";
            }
             elseif($request->status==4){
                $title = "Payment Rejected";
                $msg2 = "Your request payment  ".$txt->payable_amount." is on Rejected";
            }

        //    $registatoin_ids[]= $user->notification_id;
            $registatoin_ids[]= $editor->notification_id;
            $registatoin_ids[]= $adminUser->notification_id;
            
            $type = "Android"; 
            $message["title"]   = $title ;

            $message["action"]  = "notify";
            $message["message"] = $msg2." for order id ".$txt->order_id;

            $fcmHelper = new FCMHelper;
            $fcmHelper->send_notification($registatoin_ids,$message,$type);

        }
        
        $search = Input::get('search'); 
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('first_name','LIKE',"%$search%")
                            ->get('id')->pluck('id');

        if ((isset($search) && !empty($search))) { 
               
            $transaction = $transaction->whereHas('user')->where(function ($query) use ($search,$user,$request) {
                if (!empty($search) && !empty($user)) {
                   $query->whereIn('user_id', $user);
                   $query->orwhere('user_id', $search);
                }
                 if($request->payment_type){
                    $query->where('payment_type', $request->payment_type);
                }
            })
            ->orderBy('id','desc')->Paginate($this->record_per_page);
            
             $transaction->transform(function($item, $Key){
                            $user = User::find($item->user_id);
                            $item->user_id = $user->id??null; 
                            $item->name = $user->name??null;
                            $item->email = $user->email??null;
                            $item->phone = $user->mobile_number??null;
                            return $item;
                        });

        } else {  
            $transaction = $transaction->whereHas('user')
                        ->orderBy('id','desc')
                        ->where('payment_type',3)
                       // ->whereDate('created_at',\Carbon\Carbon::today())
                        ->Paginate(30);
                        
            $transaction->transform(function($item, $Key){
                            $user = User::find($item->user_id); 
                            $item->user_id = $user->id??null;
                            $item->name = $user->name??null;
                            $item->email = $user->email??null;
                            $item->phone = $user->mobile_number??null;
                            return $item;  
                        }); 
        }

        $deposit = \DB::table('wallet_transactions')
                    ->where('payment_type',3)
                    ->sum('amount');

        $week = \DB::table('wallet_transactions')
                ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
                ->where('payment_type',3)
                ->sum('amount');

        $month = \DB::table('wallet_transactions')
                    ->whereMonth('created_at',date('m'))
                    ->where('payment_type',3)
                    ->sum('amount');
        $today = \DB::table('wallet_transactions')
                    ->whereDate('created_at',\Carbon\Carbon::today())
                    ->where('payment_type',3)
                    ->sum('amount');
        
        return view('packages::payments.paymentHistory', compact('transaction', 'page_title', 'page_action','msg','deposit','week','month','today'));
        
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

    public function store(Request $request, Transaction $transaction) 
    {
        $transaction = Transaction::find($request->payment_id);   
        
        if($transaction){
            $editor     = User::find($transaction->editor_id);
            $adminUser  = User::find(env('DEFAULT_USER_ID'));
          
            $registatoin_ids=array();

            $registatoin_ids[]= $editor->notification_id;
            $registatoin_ids[]= $adminUser->notification_id;
            
            $type = "Android";
            $message["title"] = "Order is Ready ";
            $message["action"] = "notify";
            $message["message"] = "Your order id ".$transaction->order_id." is ready to download";
            $transaction->status = 5;
            $transaction->request_status = 2;
            $transaction->remarks = $request->remarks;
            $transaction->save();

            $fcmHelper = new FCMHelper;
            $fcmHelper->send_notification($registatoin_ids,$message,$type);
        }
        
        
        return Redirect::to(route('payments'))
                            ->with('flash_alert_notice', 'Payment Done');
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
