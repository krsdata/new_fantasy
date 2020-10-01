<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
use Input;

class DefaultContestRequest  extends Request {

    /**
     * The metric validation rules.
     *
     * @return array    
     */
    public function rules() { 
            switch ( $this->method() ) {
                case 'GET':
                case 'DELETE': {
                        return [ ];
                    }
                case 'POST': {
                        return [
                            'contest_type' => 'required',
                            'entry_fees' => 'required|numeric',
                            'total_spots' => 'required|numeric',
                            'first_prize' => 'required|numeric', 
                            'winner_percentage' => 'required',
                            'total_winning_prize' => 'required'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {
                        return [
                            'contest_type' => 'required',
                            'entry_fees' => 'required|numeric',
                            'total_spots' => 'required|numeric',
                            'first_prize' => 'required|numeric', 
                            'winner_percentage' => 'required',
                            'total_winning_prize' => 'required' 
                        ];
                    
                }
                default:break;
            }
        //}
    }

    /**
     * The
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}
