<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//判断是否登陆
function is_login()
{
    $user = session('ke_user_auth');

    if (empty($user)) {
        return 0;
    }
    else {
        return $user['aid'];
    }
}
//获取登陆uid
function is_admin()
{
    $user = session('ke_user_auth');

    if (empty($user)) {
        return 0;
    }
    else {
        return $user['aid'];
    }
}
function set_redirect_url($url)
{
    cookie('ke_redirect_url', $url);
}

function get_redirect_url()
{
    return cookie('ke_redirect_url');
}
//生成token
function creatToken($uname,$time){
   $t=date('Y-m-d h:i:s', $time) .  $uname;
    //这里是token产生的机制  您也可以自己定义
    $nonce = md5($t);
    return $nonce;

}
//获取token
function getToken(){
    $user=cache('user_auth');
    $token=$user['token'];
    return $token;
}
function u_is_login()
{
    $user = cache('user_auth');

    if (empty($user)) {
        return 0;
    }
    else {
        return $user['aid'];
    }
}
//开启缓存
function savedata($name,$data,$time){
    $options = [
        // 缓存类型为File
        'type'  =>  'File',
        // 缓存有效期为永久有效
        'expire'=>  0,
        //缓存前缀
        'prefix'=>  'think',
     // 指定缓存目录
    'path'  =>  APP_PATH.'runtime/cache/',
];
    cache($name, $data, $time);
}
function getdata($name){
    return cache($name);
}
//
