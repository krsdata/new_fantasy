<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;  
use Illuminate\Foundation\Http\FormRequest;
use Response;
use App\Models\Matches;

class Competition extends Eloquent {

   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'competitions';
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
     * use App\Models\Matches
     * @var array
     */ 
    public function match()
    {  
        return $this->hasOne('App\Models\Matches','match_id','match_id');
    }
    public function joinContest()
    {  
        return $this->hasMany('App\Models\JoinContest','id','join_contests');
    }
  
}
