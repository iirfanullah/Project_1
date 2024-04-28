<?php

namespace App\Http\Controllers;

use App\Models\meetings;
use Google_Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $meetings = meetings::where(['creator_id' => auth()->id()])->get();
        return view('home', ['meetings' => $meetings]);
    }
}
