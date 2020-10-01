<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
use Input;

class UserRequest extends Request {

    /**
     * The metric validation rules.
     *
     * @return array
     */
    public function rules() {
        //if ( $metrics = $this->metrics ) {
            switch ( $this->method() ) {
                case 'GET':
                case 'DELETE': {
                        return [ ];
                    }
                case 'POST': {
                        return [
                            
                            'name'      => 'required|min:3', 
                            'email'     => "required|email|unique:users,email" ,
                             'mobile_number' => 'required|unique:users,mobile_number'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {
                    if ( $user = $this->user ) {

                        return [
                            
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
