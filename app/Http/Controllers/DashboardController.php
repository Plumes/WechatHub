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
        $mp['agent_id'] = intval($request->input("agent_id"));
        $mp_id = MediaPlatform::createMP($mp);
    }

    public function getMenu($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $button_list = $mp->getMenu();
        return view("menu",compact("mp","button_list"));
    }

    public function getNews($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $mp->pullNewsFromRemote();
        $news_list = $mp->getNews();
        return view("news",compact("mp","news_list"));
    }

    public function editNews($mp_id,$news_id) {
        $mp = MediaPlatform::find($mp_id);
        $news = $mp->getNews($news_id)[$news_id];
        //dd($news);
        return view("news_edit",compact("mp","news"));
    }
}
