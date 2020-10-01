<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request; 
 

class BannerRequest  extends Request {

    /**
     * The product validation rules.
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
                            'title'             => 'required' ,  
                            'description'      => 'required', 
                            'photo'     => 'mimes:jpeg,bmp,png,gif'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {

                    if ( $banner = $this->banner ) {

                        return [
                             	'title'             => 'required' ,  
                            	'description'      => 'required', 
                            	'photo'     => 'mimes:jpeg,bmp,png,gif'
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
