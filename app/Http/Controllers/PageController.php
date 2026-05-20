<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function members()    { return view('pages.members'); }
    public function payments()   { return view('pages.payments'); }
    public function unpaid()     { return view('pages.unpaid'); }
    public function otherIncome(){ return view('pages.other-income'); }
    public function expenses()   { return view('pages.expenses'); }
    public function aiInsights() { return view('pages.ai-insights'); }
    public function calculator() { return view('pages.calculator'); }
    public function settings()   { return view('pages.settings'); }
}
