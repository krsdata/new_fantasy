<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model as Eloquent; 
 

class Document extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */ 
    protected $table = 'verify_documents';
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
    
    protected $fillable = [
                            'user_id'
                        ];  // All field of user table h
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 

    public function user()
    {
        return $this->hasOne('Modules\Admin\Models\User','id' , 'user_id');
    }
 
  }

