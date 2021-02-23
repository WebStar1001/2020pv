<?php
namespace App\Http\Requests\Admin;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGeneralInformationRequest extends FormRequest
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
    	$user = auth()->user();

    	if (!$user->isAdvisor()) {
		    return [
			    'display_name' => 'required|unique:users,display_name,' . $user->id,
			    'avatar'       => 'mimes:jpg,jpeg,png|max:10000'
		    ];
	    }

	    $required_rule = ['required', Rule::notIn(['[]', 'null'])];

	    return [
	    	'full_name' => $required_rule,
		    'display_name' => 'required|unique:users,display_name,' . $user->id,
		    'avatar' => 'mimes:jpg,jpeg,png|max:10000',
		    'languages' => $required_rule,
		    'master_service_id' => $required_rule,
		    'price_per_minute' => $required_rule,
		    'experience_years' => $required_rule,
		    'experience' => $required_rule,
		    'highlight' => $required_rule,
		    'description' => $required_rule,
		    'about_services' => $required_rule,
		    'qualification' => $required_rule,
	    ];
    }
}
