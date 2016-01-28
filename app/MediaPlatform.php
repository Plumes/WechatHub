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
    private $mp;

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
        return $this->getMenuFromRemote();
    }

    public function getMenuFromRemote() {
        $this->refreshToken();
        $api = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this['access_token'];
        $result = httpGet($api);
        $result = json_decode($result,true);
        $this->saveMenu($result['menu']['button']);
        return $result;

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
