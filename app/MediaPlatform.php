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
    public function __construct(array $attributes=[],$mp_id=false)
    {
        parent::__construct($attributes);
        if($mp_id) {
            $this->mp = $this->find($mp_id);
            $this->refreshToken();
        }
    }

    private function refreshToken() {
        $token_update_time = $this->mp['token_updated'];
        if((time() - $token_update_time) > 3600) {
            //如果 token 距上次更新超过一小时则再次更新
            $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->mp['appid']."&secret=".$this->mp['appsecret'];
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
        $api = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->mp['access_token'];
        $result = httpGet($api);
        $result = json_decode($result,true);
        return $result;
    }
}
