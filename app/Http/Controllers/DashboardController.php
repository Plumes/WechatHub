<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        //Auth::attempt(['name' => 'admin', 'password' => '123456']);
        $this->middleware('auth');
    }
    //
    public function index() {
        return view('welcome');
    }
}
