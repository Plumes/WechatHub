<?php
/**
 * Created by PhpStorm.
 * User: plume
 * Date: 2016/1/29
 * Time: 9:18
 */
return array(
    "service"=>array(
        "menu"=>array(
          "delete" => 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=',
          "create" => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=',
        ),
        "news"=>array(
            "pull" => 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=',
            "create" => ''
        ),
        "token"=>array(
            "get" => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s"
        )
    ),
    "qy"=>array(
        "menu"=>array(
            "get" => 'https://qyapi.weixin.qq.com/cgi-bin/menu/get?access_token=%s&agentid=%s',
            "create" => "https://qyapi.weixin.qq.com/cgi-bin/menu/create?access_token=%s&agentid=%d",
            "delete" => "https://qyapi.weixin.qq.com/cgi-bin/menu/delete?access_token=%s&agentid=%d"

        ),
        "token"=>array(
            "get" => "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s"
        )
    )

);