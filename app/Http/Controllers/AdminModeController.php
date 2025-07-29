<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminModeController extends Controller
{
    public function toggle(Request $request)
    {
        session(['admin_mode' => $request->admin_mode]);
        return response()->json(['success' => true]);
    }

    public function check()
    {
        return response()->json(['admin_mode' => session('admin_mode', false)]);
    }
}