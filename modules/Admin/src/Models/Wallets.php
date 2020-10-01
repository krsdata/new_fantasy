<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;  
use Illuminate\Foundation\Http\FormRequest;
use Response;

class Wallets extends Eloquent {

   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wallets';
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
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */ 
    public function user()
    {
       
        return $this->hasOne('Modules\Admin\Models\User','id','user_id');
    }
    
    public function order()
    { 
     return $this->hasOne('Modules\Admin\Models\PrizeDistribution','id','user_id');
    }
   
  
}
