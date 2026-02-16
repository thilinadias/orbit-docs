<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'nullable|string|max:255',
            'system_logo' => 'nullable|image|max:2048',
            'sidebar_color' => 'nullable|string|max:7',
        ]);

        if ($request->hasFile('system_logo')) {
            $path = $request->file('system_logo')->store('branding', 'public');
            Setting::set('system_logo', $path);
        }

        if ($request->has('system_name')) {
            Setting::set('system_name', $request->system_name);
        }

        if ($request->has('sidebar_color')) {
            Setting::set('sidebar_color', $request->sidebar_color);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
