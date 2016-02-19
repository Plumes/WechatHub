<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MediaPlatform extends Model
{
    //
    protected $fillable = [
        'appname', 'appid', 'appsecret','access_token',
    ];
    protected $table = 'media_platform';

    private function refreshToken() {
        $token_update_time = $this['token_updated'];
        if((time() - $token_update_time) > 3600) {
            //如果 token 距上次更新超过一小时则再次更新
            $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this['appid']."&secret=".$this['appsecret'];
            $result = httpGet($api);
            $result = json_decode($result,true);
            //$this->mp['access_token'] = $result['access_token'];
            $update_mp['access_token'] = $result['access_token'];
            $update_mp['token_updated'] = Carbon::now();
            $this->update($update_mp);
        }
    }

    public static function createMP($mp) {
        $result = MediaPlatform::create($mp);
        $mp_id = $result['id'];
        return $mp_id;
    }

    //从数据库中读取菜单
    public function getMenu() {
        $button_list = Menu::where("mp_id",$this['id'])
                            ->where("parent",0)
                            ->orderBy('order', 'asc')
                            ->get();
        foreach($button_list as $k=>$button) {
            $button_list[$k]['sub_button'] = Menu::where("mp_id",$this['id'])
                                                ->where("parent",$button['id'])
                                                ->orderBy('order', 'asc')
                                                ->get();
        }
        return $button_list;
    }

    //从微信服务器拉取菜单
    public function getMenuFromRemote() {
        $this->refreshToken();
        $api = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this['access_token'];
        $result = httpGet($api);
        $result = json_decode($result,true);
        $this->saveMenu($result['menu']['button']);
        return $result;

    }

    //推送菜单到微信服务器
    public function pushMenuToRemote($button_list) {
        $this->refreshToken();
        //需要先执行删除操作，把现有菜单全部删除
        $api = config("wechat_api.menu.delete");
        $api .= $this['access_token'];
        $result = httpGet($api);
        $api = config("wechat_api.menu.create");
        $api .= $this['access_token'];

        foreach($button_list as $k=>$button) {
            $sub_btn_list = [];
            if(count($button['sub_button'])>0) {
                foreach($button['sub_button'] as $i=>$child_button) {
                    $sub_btn_list[] = $button_list[$k]['sub_button'][$i];
                }
            }
            unset($button_list[$k]['sub_button']);
            $button_list[$k]['sub_button'] = $sub_btn_list;
        }

        $result = httpPost($api,json_encode(array("button"=>$button_list),JSON_UNESCAPED_UNICODE));
        return $result;
        //$result = json_decode($result,true);
        //return array("button"=>$button_list);

    }

    //保存菜单到数据库
    public function saveMenu($button_list) {
        Menu::where("mp_id",$this['id'])->delete();
        foreach($button_list as $k=>$button) {
            $new_btn = array(
                'type'=>isset($button['type'])?$button['type']:"",
                'name'=>$button['name'],
                'key'=>isset($button['key'])?$button['key']:"",
                'url'=>isset($button['url'])?$button['url']:"",
                'order'=>$k,
                'parent'=>0,
                'mp_id'=>$this['id']
            );
            $parent_btn = Menu::create($new_btn);
            if(count($button['sub_button'])>0) {
                foreach($button['sub_button'] as $i=>$child_button) {
                    $new_btn = array(
                        'type'=>isset($child_button['type'])?$child_button['type']:"",
                        'name'=>$child_button['name'],
                        'key'=>isset($child_button['key'])?$child_button['key']:"",
                        'url'=>isset($child_button['url'])?$child_button['url']:"",
                        'order'=>$i,
                        'parent'=>$parent_btn['id'],
                        'mp_id'=>$this['id']
                    );
                    Menu::create($new_btn);
                }
            }
        }
    }

    //从数据库中读取图文消息列表，一个图文消息 news 由若干 article 组成
    public function getNews($news_media_id=false) {
        $condition = [ ["mp_id",$this['id']] ];
        if($news_media_id) {
            $condition[] = ["news_media_id",$news_media_id];
        }
        $result = Article::where($condition)->orderBy("id", "asc")->orderBy("order","asc")->get();
        $article_list = [];
        foreach($result as $article) {
            $article_list[$article['news_media_id']][$article['order']] = $article;
        }
        return $article_list;
    }

    //从微信服务器拉取图文消息列表
    public function pullNewsFromRemote() {
        $this->refreshToken();
        $api = config("wechat_api.news.pull").$this['access_token'];
        $post_data = array(
            "type"=>"news",
            "offset"=>0,
            "count"=>20
        );
        $result = httpPost($api,json_encode($post_data));
        $news_list = json_decode($result[1],true);
        foreach($news_list['item'] as $news) {
            $news_media_id = $news['media_id'];
            foreach($news['content']['news_item'] as $k=>$article) {
                $article['news_media_id'] = $news_media_id;
                $article['order'] = $k;
                $article['mp_id'] = $this['id'];

                //处理外链图片
                $pattern = "/http:\/\/mmbiz.qpic.cn/";
                $replacement = "https://mmbiz.qlogo.cn";
                $article['thumb_url'] = preg_replace($pattern, $replacement, $article['thumb_url']);
                $article['content'] = preg_replace($pattern, $replacement, $article['content']);

                $t_article = Article::firstOrCreate(["news_media_id"=>$news_media_id,"order"=>$k]);
                foreach($article as $k=>$v) {
                    $t_article[$k] = $v;
                }
                $t_article->save();
            }
        }
    }
}
