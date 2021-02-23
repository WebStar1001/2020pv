<?php
namespace App\Http\Requests\Admin;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisorRequest extends FormRequest
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
	        'role_id' => 'required',
	        'full_name' => 'required',
            'display_name' => 'required|unique:users,display_name',
	        'email' => 'required|email|unique:users,email',
	        'country' => 'required',
	        'avatar' => 'required|mimes:jpg,jpeg,png|max:10000',
	        'resume' => 'required|mimes:pdf|max:10000',
        ];
    }
}
