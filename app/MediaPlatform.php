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

    public function getMenuFromRemote() {
        $this->refreshToken();
        $api = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this['access_token'];
        $result = httpGet($api);
        $result = json_decode($result,true);
        $this->saveMenu($result['menu']['button']);
        return $result;

    }

    public function pushMenuToRemote($button_list) {
        $this->refreshToken();
        //需要先执行删除操作，把现有菜单全部删除
        $api = config("wechat_api.menu.delete");
        $api .= $this['access_token'];
        $result = httpGet($api);
        $api = config("wechat_api.menu.create");
        $api .= $this['access_token'];

        foreach($button_list as $k=>$button) {
            //$button_list[$k]['name'] = urlencode($button_list[$k]['name']);
            //$button_list[$k]['key'] = urlencode($button_list[$k]['key']);
            //$button_list[$k]['url'] = urlencode($button_list[$k]['url']);
            $sub_btn_list = [];
            if(count($button['sub_button'])>0) {
                foreach($button['sub_button'] as $i=>$child_button) {
                    //$button_list[$k]['sub_button'][$i]['name'] = urlencode($button_list[$k]['sub_button'][$i]['name']);
                    //$button_list[$k]['sub_button'][$i]['key'] = urlencode($button_list[$k]['sub_button'][$i]['key']);
                    //$button_list[$k]['sub_button'][$i]['url'] = urlencode($button_list[$k]['sub_button'][$i]['url']);
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
}
