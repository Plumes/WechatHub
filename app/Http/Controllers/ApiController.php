<?php

namespace App\Http\Controllers;

use App\Article;
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

    public function saveMenu($mp_id, Request $request) {
        $mp = MediaPlatform::find($mp_id);
        $post_data = $request->all();
        $mp->saveMenu($post_data['data']);
        $result = $mp->pushMenuToRemote($post_data['data']);
        echo(json_encode($result));
    }

    public function getArticle($article_id) {
        $article = Article::find($article_id);
        echo json_encode(['code'=>0,'article'=>$article]);
    }

    public function pullNews($mp_id) {
        $mp = MediaPlatform::find($mp_id);
        $mp->pullNewsFromRemote();
        echo(json_encode(['code'=>0]));
    }
}
