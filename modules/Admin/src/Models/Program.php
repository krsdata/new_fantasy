<?php
namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Program extends Model {

   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'programms';
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
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'campaign_name',
            'start_date',
            'end_date',
            'description',
            'reward_type',
            'amount',
            'promotion_type',
            'status',
            'trigger_condition',
            'customer_type',
            'created_by',
            'start_time',
            'end_time'
        ];  // All field of user table here    

    
    
  
}
