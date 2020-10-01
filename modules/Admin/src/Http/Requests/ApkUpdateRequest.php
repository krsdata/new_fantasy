<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request; 
 

class ApkUpdateRequest  extends Request {

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
                            'title'       => 'required' ,  
                            'version_code' => 'required', 
                            'apk'       => 'required'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {

                    if ( $apkUpdate = $this->apkUpdate ) {

                        return [
                             	'title'             => 'required' ,  
                            	'version_code'      => 'required',  
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
