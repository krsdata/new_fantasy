<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
use Input;

class CategoryRequest extends Request
{

    /**
     * The metric validation rules.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
                case 'GET':
                case 'DELETE': {
                        return [ ];
                    }
                case 'POST': {
                        return [
                            'category_name' => 'required|unique:categories,category_name',
                             'category_image'             => 'required|mimes:jpeg,bmp,png,gif'
                        ];
                    }
                case 'PUT':
                case 'PATCH': {
                    if ($category = $this->category) {
                        return [
                            'category_name'   => "required" ,
                            
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
    public function authorize()
    {
        return true;
    }
}
