<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
use Input;

class ContestTypeRequest  extends Request {

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
                            'contest_type' => 'required|unique:contest_types,contest_type', 
                             'max_entries'  => 'required|numeric'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {
                    if ( $contestType = $contestType->contestType) {

                        return [
                            'contest_type' => 'required', 
                            'max_entries'  => 'required|numeric' 
                        ];
                    }
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
