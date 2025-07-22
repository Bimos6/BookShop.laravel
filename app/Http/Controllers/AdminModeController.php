<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminModeController extends Controller
{
    public function toggle(Request $request)
    {
        $setting = Setting::first();
        $setting->update([
            'admin_mode' => !$setting->admin_mode
        ]);
        
        return back()->with('success', 
            $setting->admin_mode 
                ? 'Режим редактирования включен' 
                : 'Режим редактирования отключен');
    }
    
    public function status()
    {
        return response()->json([
            'admin_mode' => Setting::first()->admin_mode
        ]);
    }
}
