<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeExpenseAddStoreRequest extends FormRequest
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
            'expense_date' =>'required',
            'category_id' => 'required|not_in:0',
            'expense_description' => 'required|string|max:255',
            'pre_tax_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'tax_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}
