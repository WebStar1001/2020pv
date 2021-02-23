<?php

namespace App\Http\Controllers;

use App\Country;
use App\Horoscope;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteAccountRequest;
use App\Http\Requests\Admin\UpdateGeneralInformationRequest;
use App\Language;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
	public function __construct()
	{

	}

	public function getGeneralInformation()
	{
		$user = User::with(['languages', 'services'])->findOrFail(auth()->id());

		foreach ($user->languages as $key => $language) {
			$user->languages[$key]['id'] = $language->language->id;
			$user->languages[$key]['title'] = $language->language->title;
		}

		foreach ($user->services as $key => $service) {
			$user->services[$key]['id'] = $service->service->id;
			$user->services[$key]['title'] = $service->service->title;
		}

		return response()->json([
			'user' => $user,
			'countries' => Country::all(),
			'languages' => Language::all(),
			'horoscopes' => Horoscope::all(),
			'master_services' => Service::where(['master_service' => 1])->get(),
			'services' => Service::all(),
		]);
	}

	public function updateGeneralInformation(UpdateGeneralInformationRequest $request)
	{
		$user = auth()->user();
		$data = $request->all();

		if ($request->hasFile('avatar')) {
			$data['avatar'] = $request->file('avatar')->store('avatars');

			// delete old avatar
			if ($user->avatar && Storage::exists($user->avatar)) {
				Storage::delete($user->avatar);
			}
		} else {
			unset($data['avatar']);
		}

		foreach ($data as $key => $value) {
			if ($value === 'null') {
				$data[$key] = null;
			}
		}

		$slug_is_busy = User::where(['slug' => $this->slugify($data['display_name'])])->first();

		if ($slug_is_busy && $slug_is_busy->id !== $user->id) {
			$data['slug'] = $this->slugify($data['display_name']) . '-' . $user->id;
		} else {
			$data['slug'] = $this->slugify($data['display_name']);
		}

		$user->update($data);

		$user->languages()->delete();

		if ($data['languages']) {
			foreach (json_decode($data['languages']) as $language) {
				$user->languages()->create([
					'language_id' => $language->id
				]);
			}
		}

		$user->services()->delete();

		if ($data['services']) {
			foreach (json_decode($data['services']) as $service) {
				$user->services()->create([
					'service_id' => $service->id
				]);
			}
		}

		return response()->json(true);
	}

	private function slugify($string){
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public function updateSecurity(Request $request)
	{
		$user = auth()->user();
		$this->validator($request->all())->validate();

		if (Hash::check($request->get('current_password'), $user->password)) {
			$user->password = $request->get('new_password');
			$user->save();
			return response()->json(['success' => 'Password change successfully!']);
		} else {
			return response()->json(['error' => 'Current password is incorrect']);
		}
	}

	/**
	 * Get a validator for an incoming change password request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'current_password' => 'required',
			'new_password' => 'required|min:6',
		]);
	}

	public function deleteAccount(DeleteAccountRequest $request)
	{
		$user = auth()->user();

		if (Hash::check($request->get('password'), $user->password)) {
			$user->update(['deleted' => 1]);

			return response()->json(['success' => 'Account Deleted']);
		} else {
			return response()->json(['error' => 'Password is incorrect']);
		}
	}

	public function updateNotifications(Request $request)
	{
		auth()->user()->update(['subscribed' => $request->subscribed]);

		return response()->json(true);
	}

	public function getPaymentData()
	{
		$user = auth()->user();

		$pending = $user->pendingPaymentEmails()->where(['pending' => 1])->first();

		return response()->json([
			'pending' => $pending ? true : false,
			'countries' => Country::all(),
			'country' => $user->country,
			'paypal_payment' => $user->paypal_payment,
			'bank_details' => $user->bankDetail
		]);
	}

	public function updatePaymentData(Request $request)
	{
		$user = auth()->user();

		if ($request->email) {
			$user->pendingPaymentEmails()->create( [
				'email' => $request->email
			] );
		}

		$user->update([
			'country' => $request->country,
			'paypal_payment' => $request->paypal_payment
		]);

		if ($request->country === 'Pakistan') {
			$user->bankDetail()->updateOrCreate(['user_id' => $user->id], $request->bank_details);
		}

		return response()->json(true);
	}

}
