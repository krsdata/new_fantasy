<?php
namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PrizeBreakups extends Model {

   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prize_breakups';
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
            'default_contest_id',
            'contest_type_id',
            'rank_from',
            'rank_upto',
            'prize_amount',
            'match_id' 
        ];  // All field of user table here


    public function contestType()
    {
        return $this->hasOne('Modules\Admin\Models\ContestType','id','contest_type_id');
    }

     public function defaultContest()
    {
        return $this->hasOne('Modules\Admin\Models\DefaultContest','id','default_contest_id');
    }
}
