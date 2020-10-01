<?php
namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultContest extends Model {

    use SoftDeletes;
     
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'default_contents';
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
            'contest_type',
            'entry_fees',
            'total_spots',
            'filled_spot',
            'first_prize',
            'winner_percentage',
            'cancellation',
            'total_winning_prize',
            'match_id',
            'prize_percentage',
            'is_free'
        ];  // All field of user table here


    public function contestType()
    {
        return $this->hasOne('Modules\Admin\Models\ContestType','id','contest_type');
    }
}
