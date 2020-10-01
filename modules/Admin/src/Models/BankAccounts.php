<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model as Eloquent; 
 

class BankAccounts extends Eloquent {

     
   
    /**
     * The database table used by the model.
     *
     * @var string
     */ 
    protected $table = 'bank_accounts';
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
    protected $fillable = ['eid','before_img','after_img' ];

    public function user()
    {
        return $this->hasOne('Modules\Admin\Models\User','id' , 'user_id')->select('id','first_name','last_name','email','profile_image','phone');
    }
 
  }

