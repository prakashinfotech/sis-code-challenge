<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeExpenseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	//'import_file' => ['required','file','mimes:psv','max:'.$this->convertFileSize(config('filesystems.max_allowed_upload_size'),'m')]
       		'import_file' => 'required|file|mimes:psv,txt|max:'.$this->convertFileSize(config('filesystems.max_allowed_upload_size'),'m')
        ];
    }
    /*
     * Converts filesize to bytes.
     */
    public function convertFileSize($val, $returnType = 'm') {
    	$val = trim($val);
    	$last = $val[strlen($val)-1];
    	
    	$val = trim(str_replace($last, '', $val));
    	$last = strtolower($last);
    	
    	switch($last){
    		case 'g':
    			$val *= 1024;
    			if($returnType== 'g'){
    				break;
    			}
    		case 'm':
    			$val *= 1024;
    			if($returnType== 'm'){
    				break;
    			}
    		case 'k':
    			$val *= 1024;
    			if($returnType== 'k'){
    				break;
    			}
    	}
    	return $val;
    }
}
