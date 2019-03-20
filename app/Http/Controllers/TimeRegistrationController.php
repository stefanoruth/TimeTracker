<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TimeRegistrationController extends Controller
{
    public function index(Request $request)
    {
        return Auth::user()->timeRegistrations()->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->toSql();
    }
}
