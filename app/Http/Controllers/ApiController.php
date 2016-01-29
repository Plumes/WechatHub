<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MediaPlatform;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    //
    public function getMenu($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $button_list = $mp->getMenu();
        echo (json_encode(['code'=>0,"button_list"=>$button_list]));
    }

    public function pullMenu($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $button_list = $mp->getMenuFromRemote();
        echo (json_encode(['code'=>0,"button_list"=>$button_list]));
    }
}
