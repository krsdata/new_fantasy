<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\EditorPortfolioRequest;
use Modules\Admin\Models\User;
use Input,Validator, Auth, Paginate, Grids, HTML, Form;
use Hash, View, URL, Lang, Session, DB, Route, Crypt, Str;
use Illuminate\Http\Dispatcher;
use App\Helpers\Helper;
use Modules\Admin\Models\Roles;
use Modules\Admin\Models\EditorPortfolio;
use Modules\Admin\Models\Category;
use Modules\Admin\Models\SoftwareEditor;
use Modules\Admin\Models\EditorPosts as EditorPost;
use Modules\Admin\Models\Document;
use Modules\Admin\Models\BankAccounts;
use Illuminate\Support\Facades\Cache;

/**
 * Class AdminController
 */
class DocumentController extends Controller
{
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
        View::share('viewPage', 'documents');
        View::share('sub_page_title', 'Document');
        View::share('helper', new Helper);
        View::share('heading', 'Customer Document');
        View::share('route_url', route('documents'));
        $this->record_per_page = Config::get('app.record_per_page');
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
     * Dashboard
     * */

    public function bankAccount(BankAccounts $bankAccounts, Request $request){

        $page_title     = 'Document';
        $sub_page_title = 'Document';
        $page_action    = 'View Bank Accounts';
        
        // Search by name ,email and  
        $search = Input::get('search'); 
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('first_name','LIKE',"%$search%")
                            ->orWhere('name','LIKE',"%$search%")
                            ->get('id')->pluck('id');
        
        if ((isset($search) && !empty($search))) {

            $documents = BankAccounts::whereHas('user')->where(function ($query) use ($search,$user) {
                if (!empty($search) && $user->count()) {
                    
                   $query->whereIn('user_id', $user);
                }elseif ($user->count()==0) { 
                    $query->orWhere('bank_name','LIKE',"%$search%");
                    $query->orWhere('account_name','LIKE',"%$search%");
                    $query->orWhere('account_number','LIKE',"%$search%");
                    $query->orWhere('ifsc_code','LIKE',"%$search%");
                }
            })->orderBy('id','desc')->Paginate($this->record_per_page);
        } else { 
            $documents = BankAccounts::whereHas('user')
                        ->orderBy('id','desc')
                        ->Paginate($this->record_per_page);
                        
        }
        return view('packages::documents.bank', compact('documents', 'page_title', 'page_action', 'sub_page_title'));
    }

    public function index(Document $documents, Request $request)
    {   
        $page_title     = 'Document';
        $sub_page_title = 'Document';
        $page_action    = 'View Document'; 
        
        // Search by name ,email and  
        $search = Input::get('search'); 
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('name','LIKE',"%$search%")
                            ->orWhere('team_name','LIKE',"%$search%")
                            ->get('id')->pluck('id');
        $approved = Document::where('status',2)->count();
        $pending  =  Document::where('status','!=',2)->count();

        if ((isset($search) && !empty($search))) {

            $documents = Document::with('user')->where(function ($query) use ($search,$user) {
                if (!empty($search) && !empty($user)) {
                   $query->whereIn('user_id', $user);
                }
            })
            ->orderBy('status','asc')
           // ->orderBy('id','desc')
            ->Paginate($this->record_per_page);
           // dd($documents);
            $documents->transform(function($item,$key){
                $bankAccount = \DB::table('bank_accounts')->where('user_id',$item->user_id)->first();
                $item->bankAccount = $bankAccount;

                $wallet = \DB::table('wallets')->where('user_id',$item->user_id)
                    ->where('payment_type','!=',1)
                    ->sum('amount');
                $item->wallet_balance =   $wallet;

                return $item;
            });
        } else {
            $documents = Document::with('user')
                        ->orderBy('status','asc')
                       // ->groupBy('user_id')
                        ->Paginate($this->record_per_page);

            $documents->transform(function($item,$key){
                $bankAccount = \DB::table('bank_accounts')
                            ->where('user_id',$item->user_id)
                            ->first();

                $item->account_name = $bankAccount->account_name??null;
                $item->bank_name = $bankAccount->bank_name??null;
                $item->account_number = $bankAccount->account_number??null;
                $item->ifsc_code = $bankAccount->ifsc_code??null;
                $item->bank_branch = $bankAccount->bank_branch??null;
                $item->account_type = $bankAccount->account_type??null;

                $wallet = \DB::table('wallets')->where('user_id',$item->user_id)
                    ->where('payment_type','!=',1)
                    ->sum('amount');
                $item->wallet_balance =   $wallet;
                $user = User::find($item->user_id);
                $item->user = $user;
                $item->bankAccount = $bankAccount;

                $bank = \DB::table('bank_accounts')
                            ->where('user_id',$item->user_id)
                            ->first();
                $item->bank = $bank;            

                return $item;
            });

        }
        //return ($documents);
        return view('packages::documents.index', compact('documents', 'page_title', 'page_action', 'approved','pending'));
    }

    /*
     * create   method
     * */

    public function create(Document $documents)
    {
         return Redirect::to(route('documents'))
                            ->with('flash_alert_notice', 'You can not create Document');
    }
    /*
     * Save   method
     * */
    public function store(Request $request, Document $documents)
    {   
        if($request->doc_id){
           // dd(1);
            $documents1 =  Document::where('id',$request->doc_id)->first();
             $return_url = route('documents');
             $msg = 'Document status  successfully  updated!';
           //  $request->merge(['bank_doc_uid'=>$documents->user_id]);
            $documents1->status  = $request->document_status;
            $documents1->notes   = $request->notes;
        }

        if($request->bank_doc_id){
            $documents2 = BankAccounts::where('id',$request->bank_doc_id)->first();
            $return_url = 'admin/bankAccount';
            $msg = 'Bank Account status  successfully  updated!';
            $documents2->status  = $request->document_status;
            $documents2->notes   = $request->notes;
        }

        if($request->doc_id && $request->bank_doc_id){
            
            $documents1->save(); 
            $documents2->save();
            $msg = "Document Verified"; 
        }else{
            if($request->document_status==2){

            }else{
                if($request->doc_id){
                    $documents1->save();
                }
                if($request->bank_doc_id){
                    $documents2->save();
                }
            }
            $msg = "User has not uploaded bank account details";
        }
        $uid    = $documents1->user_id??$documents2->user_id;

        $user   = User::find($uid); 

        if($request->document_status==4){
            $documents1 = Document::where('user_id',$user->id)->delete();
            $documents2 = BankAccounts::where('user_id',$user->id)->delete();
        }

        if($user){
                $token = $user->device_id;
                $msg = $request->notes??'Under Review';
                $data = [
                        'action' => 'notify' ,
                        'title' => "Document Verification Status",
                        'message' => $msg
                    ];
                $this->sendNotification($token, $data);
                
           }

        return Redirect::to('admin/documents')
                            ->with('flash_alert_notice', $msg);
    }
    /*
     * Edit   method
     * @param
     * object : $documents
     * */
    public function edit( Request $request ,$id)
    {
        $editor = User::where('role_type',5)->pluck('first_name','id');
        $editorPost  = EditorPost::find($id);
        $page_title = '  Document';
        $page_action = 'Edit   Document';
        $url = '';//url::asset('storage/uploads/editorPortfolio/'.$editorPost->image_name)  ;
        
        $category_name = Category::pluck('category_name','id');
        $software_editor = SoftwareEditor::pluck('software_name','id');

        return view('packages::documents.edit', compact('url', 'editorPost', 'page_title', 'page_action','category_name','software_editor','editor'));
    }

    public function update(EditorPortfolioRequest $request, $id)
    { 
        return Redirect::to(route('documents'))
                        ->with('flash_alert_notice','Document successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     *
     */
    public function destroy($id)
    {
        Document::where('id', $id)->delete();
        return Redirect::to(route('documents'))
                        ->with('flash_alert_notice', '  Document successfully deleted.');
    }

    public function show(Document $document)
    {
    }
}
