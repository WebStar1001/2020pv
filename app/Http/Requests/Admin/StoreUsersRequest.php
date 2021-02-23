<?php
namespace App\Http\Requests\Admin;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
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
            'display_name' => $this->request->get('role_id') == Role::getAdvisorId() ? 'required' : '',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
	        'price_per_minute' => $this->request->get('role_id') == Role::getAdvisorId() ? 'required|numeric' : '',
            'commission_percentage' => $this->request->get('role_id') == Role::getAdvisorId() ? 'required|integer' : '',
	        'mimes:jpg,jpeg,png|max:10000'
        ];
    }
}
