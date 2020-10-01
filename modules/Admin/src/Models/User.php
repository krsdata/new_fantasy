<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Admin\Models\Group;
use Modules\Admin\Models\Position;
use Auth;
use URL;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable {

    use SoftDeletes;
     
    protected $dates = ['deleted_at'];

   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     /**
     * The primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
                            'name',
                            'profile_image',
                            'mobile',
                            'email', 
                            'role_type',
                            'password',
                            'status',
                            'user_name',
                            'mobile_number',
                            'referal_code',
                            'reference_code',
                            'team_name',
                            "dateOfBirth",
                            "state",
                            "block_referral",
                            "affiliate_user",
                            "affiliate_commission"
                        ];  // All field of user table h


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $guarded = ['created_at' , 'updated_at' , 'id' ];

    // Return user record
    public function getUserDetail($id=null)
    {
        if($id){
            return User::find($id); 
        }
        return User::all();
    }

    public static function createImage($request, $fielName)
    {
        try{
           
            if ($request->file($fielName)) {
                $photo = $request->file($fielName);

                $destinationPath = storage_path('uploads/profile/');
                $photo->move($destinationPath, time().$photo->getClientOriginalName());
                $photo_name = time().$photo->getClientOriginalName();
                return  URL::asset('storage/uploads/profile/'.$photo_name);
                //$request->merge(['photo'=>$photo_name]);
            }else{
                 return false;
            }  
            
        }catch(Exception $e){
            return false;
        }
        
    }

    public static function uploadDocs($request, $fielName)
    {
        try{
           
            if ($request->file($fielName)) {
                $photo = $request->file($fielName);

                $destinationPath = storage_path('uploads/documents/');
                $photo->move($destinationPath, time().$photo->getClientOriginalName());
                $photo_name = time().$photo->getClientOriginalName();
                return  URL::asset('storage/uploads/documents/'.$photo_name);
                
            }else{
                 return false;
            }  
            
        }catch(Exception $e){
            return false;
        }
        
    }

    public function role(){
        return $this->belongsTo('Modules\Admin\Models\Role','user_id');
    }


}