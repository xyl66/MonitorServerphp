<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2017/3/24
 * Time: 上午 09:07
 */

namespace app\apilib;
use think\Request;

class base
{
    public function __construct()
    {
        $user=getdata('user_auth'.request()->ip());
        if (!$user) {
            return json(['msg'=>'no login','status'=>0]);
        }
        $uid=$user['aid'];
        //权限认证
        $auth = new \Auth\Auth();
        $request = Request::instance();
        return json(['msg'=>$request,'status'=>1]);
        if (!$auth->check($request->module() . '-' . $request->controller() . '-' . $request->action(), SID)) {// 第一个参数是规则名称,第二个参数是用户UID
            /* return array('status'=>'error','msg'=>'有权限！');*/
            return json(['msg'=>'你没有权限','status'=>0]);
        }
    }
}