<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'head_code' => SiteSetting::get('head_code'),
            'body_start_code' => SiteSetting::get('body_start_code'),
            'body_end_code' => SiteSetting::get('body_end_code'),
        ];

        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        SiteSetting::set('head_code', $request->input('head_code', ''));
        SiteSetting::set('body_start_code', $request->input('body_start_code', ''));
        SiteSetting::set('body_end_code', $request->input('body_end_code', ''));

        return redirect()->route('dashboard.settings.index')->with('success', 'Settings saved successfully.');
    }
}
