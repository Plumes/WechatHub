<?php

namespace App\Http\Controllers;

use App\MediaPlatform;
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
        $mp = new MediaPlatform(['id'=>1]);
        return view('welcome');
    }

    public function getCreate() {
        return view('create');
    }

    public function postCreate(Request $request) {
        $mp['appname'] = $request->input("appname");
        $mp['appid'] = $request->input("appid");
        $mp['appsecret'] = $request->input("appsecret");
        $mp_id = MediaPlatform::createMP($mp);
    }

    public function getMenu($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $menu_list = $mp->getMenu();
        //dd($menu_list);
    }
}
