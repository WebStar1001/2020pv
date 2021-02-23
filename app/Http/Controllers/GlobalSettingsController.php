<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class GlobalSettingsController extends Controller
{
	public function __construct()
	{

	}

	public function getSettings(Request $request)
	{
		$settings = Setting::all()->pluck('value', 'key')->toArray();

		return [
			'settings' => $settings,
		];
	}

	public function updateOrCreateSettings(Request $request)
	{
		foreach ($request->settings as $setting) {
			Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
		}

		return response()->json(true);
	}

}
