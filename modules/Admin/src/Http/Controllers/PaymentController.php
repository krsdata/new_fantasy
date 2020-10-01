<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request; 
use Modules\Admin\Models\User;
use Modules\Admin\Models\Category; 
use Modules\Admin\Models\Transaction;
use Modules\Admin\Models\Orders;
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
class PaymentController extends Controller {
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
        View::share('heading', 'Editor Transaction');
        View::share('route_url', route('transaction'));
        $this->record_per_page = Config::get('app.record_per_page');
    }
    protected $categories;

    /*
     * Dashboard
     * */

    public function index(Orders $transaction, Request $request) 
    { 
       
        $page_title = 'Payment';
        $page_action = 'View Transaction'; 
        if ($request->ajax()) {
            $id = $request->get('id'); 
            $result = Orders::find($id); 
            $result->status = $s;
            $result->save();
        }
         
        $search = Input::get('search'); 
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('first_name','LIKE',"%$search%")
                            ->pluck('id')->toArray();
       
        if ((isset($search) && !empty($search))) { 
               
            $transaction = $transaction->with('editor','user','payment')
                    ->where(function ($query) use ($search,$user) {
                
                 if (count($user)) {  
                   $query->whereIn('editor_id', $user);
                }else{ 
                       $query->orWhere('order_id', $search); 
                }
                 
            })
                    ->orderBy('editor_status','DESC')
                    ->Paginate($this->record_per_page);

        } else {

            $transaction = $transaction->with('editor','user','payment')
                        
                        ->select("*",
                        \DB::raw('(CASE 
                        WHEN status = 1 THEN "Pending"
                        WHEN status = 2 THEN "In Progress"
                        WHEN status = 3 THEN "Rejected/Cancel"
                        WHEN status = 4 THEN "Completed" 
                        WHEN status = 5 THEN "Approved"
                        ELSE "Pending" 
                        END) AS status'),
                        \DB::raw('(CASE 
                        WHEN order_status = 1 THEN "Pending"
                        WHEN order_status = 2 THEN "Payment Done"
                        WHEN order_status = 3 THEN "Order Accepted"
                        WHEN order_status = 4 THEN "Completed" 
                        WHEN order_status = 5 THEN "Downloaded"
                        ELSE "Pending" 
                        END) AS order_status'),
                    \DB::raw('(CASE 
                        WHEN editor_status = 1 THEN "Pending"
                        WHEN editor_status = 2 THEN "In Progress"
                        WHEN editor_status = 3 THEN "Cancel/Rejected"
                        WHEN editor_status = 4 THEN "Completed" 
                        WHEN editor_status = 5 THEN "Approved"
                        ELSE "Pending" 
                        END) AS editor_status'))
                        ->orderBy('editor_status','DESC')
                         ->Paginate($this->record_per_page);
        }
        
        return view('packages::payments.orders', compact('transaction', 'page_title', 'page_action'));
   
    }

    /*
     * create  method
     * */

    public function create(Transaction $product) 
    {
        return Redirect::to(route('payments'))
                            ->with('flash_alert_notice', 'New Transaction was successfully created !');
     }

    /*
     * Save Group method
     * */

    public function store(Request $request, Transaction $payment) 
    {
        
       
        $orders = Orders::find($request->order_id);
        $payment = Transaction::firstOrCreate(['order_id'=>$orders->id]);

        $payment->editor_id         = $orders->editor_id;
        $payment->order_id          = $orders->id;
        $payment->amount            = (float)$orders->total_price;
        $payment->service_charge    = (float)$request->service_charge;
        $payment->payable_amount    = (float)$request->amount;
        $payment->remarks           = $request->remarks;
        $payment->mode              = 'Online';
        $payment->status            = 6; // amount added
        $payment->save();

         //   $user       = User::find($orders->user_id);
            $editor     = User::find($orders->editor_id);
            $adminUser  = User::find(env('DEFAULT_USER_ID'));

            $registatoin_ids=array();

         //   $registatoin_ids[]= $user->notification_id;
            $registatoin_ids[]= $editor->notification_id;
            $registatoin_ids[]= $adminUser->notification_id;
           
            $type = "Android";
            $message["title"] = "Payment added in wallet";
            $message["action"] = "notify";
            $message["message"] = "Amount added in wallet ".$payment->payable_amount;

            $fcmHelper = new FCMHelper;
            $fcmHelper->send_notification($registatoin_ids,$message,$type);

        return Redirect::to(route('transaction'))
                            ->with('flash_alert_notice', 'Amount successfully Added for Order ID '.$orders->order_id);
       
        
    }
    /*
     * Edit Group method
     * @param 
     * object : $category
     * */

    public function edit(Transaction $transaction) {

        return Redirect::to(route('payments'))
                            ->with('flash_alert_notice', 'New Transaction was successfully created !');
    }

    public function update(Request $request, Transaction $transaction) 
    {
        return Redirect::to(route('payments'))
                            ->with('flash_alert_notice', 'New Transaction was successfully created !');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    // public function destroy(Transaction $Transaction) {
        
    //     Transaction::where('id',$product->id)->delete();

    //     return Redirect::to(route('transaction'))
    //                     ->with('flash_alert_notice', 'Transaction was successfully deleted!');
    // }

    public function show(Product $product) {
        
    }

}
