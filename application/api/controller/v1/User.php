<?php
namespace app\api\controller\v1;
use app\api\model\User as UserModel;
use think\Request;
use \think\Session;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');
class User
{
    //获取用户信息
    public function read($id=0){
        
        $user=UserModel::get($id);
        if($user){
            return json($user);
        }
        else{
            return json(['error'=>'用户不存在'],404);
        }
    }
    public function login(){
        if(Request::instance()->isPost()){
            $user=input('user/a');
            if($user['uname']==''){
                return json(array('status'=>'error','msg'=>'用户名不能为空！'));
            }
            if($user['pwd']==''){
                return json(array('status'=>'error','msg'=>'密码不能为空！'));
            }
            $User=new \app\api\model\User();
            $admin=$User->where(array('account'=>$user['uname']))->find();
            if($admin==Null){
                return json(array('status'=>'error','msg'=>'用户名不存在！'));
            }
            if($user['pwd']!=$admin['password']){
                return json(array('status'=>'error','msg'=>'密码错误！'));
            }
            $time = time();
            $token=creatToken($user['uname'],$time);
            $auth = array('aid' => $admin['admin_id'], 'last_time' => $time,'account'=>$admin['account'],'token'=>$token);
            savedata('user_auth'.request()->ip(), $auth,3600);
            return json(array('status' => 1, 'token' => $token,'request'=>request()));
        }
        if (u_is_login()) {
            $rurl=get_redirect_url();
            if ($rurl&&$rurl!='/') {
                return array('status' => 1, 'url' => $rurl);
            }
            else {
                return array('status' => 1, 'url' => Url('Admin/Index/rule'));
            }
        }
        return array('status' => 0, 'url' => 'shibai');
    }
}