<?php
namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use think\Request;
use \think\Session;
use think\Db;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');

class User
{
    //初始化权限判断
    public function __construct()
    {
        $user=getdata('user_auth'.request()->ip());
        /*if (!$user) {
            return json(['msg'=>'no login','status'=>0]);
        }*/
        $uid=$user['aid'];
        //权限认证
        $auth = new \Auth\Auth();
        $request = Request::instance();
        if (!$auth->check($request->module() . '-' . $request->controller() . '-' . $request->action(), $uid)) {// 第一个参数是规则名称,第二个参数是用户UID
            Session::set('name',0);
        }
        else
            Session::set('name',1);
    }
    //获取用户信息
    public function read($id = 0)
    {

        $user = UserModel::get($id);
        if ($user) {
            return json($user);
        } else {
            return json(['error' => '用户不存在'], 404);
        }
    }

    public function login()
    {
        if (Request::instance()->isPost()) {
            $user = input('user/a');
            if ($user['uname'] == '') {
                return json(array('status' => 'error', 'msg' => '用户名不能为空！'));
            }
            if ($user['pwd'] == '') {
                return json(array('status' => 'error', 'msg' => '密码不能为空！'));
            }
            $User = new \app\api\model\User();
            $admin = $User->where(array('account' => $user['uname']))->find();
            if ($admin == Null) {
                return json(array('status' => 'error', 'msg' => '用户名不存在！'));
            }
            if ($user['pwd'] != $admin['password']) {
                return json(array('status' => 'error', 'msg' => '密码错误！'));
            }
            if (!$admin['status']) {
                return json(array('status' => 'error', 'msg' => '用户帐号被锁定，请联系管理员解锁！'));
            }
            $time = time();
            $token = creatToken($user['uname'], $time);
            $auth = array('aid' => $admin['id'], 'last_time' => $time, 'account' => $admin['account'], 'token' => $token);
            savedata('user_auth' . request()->ip(), $auth, 3600);
            return json(array('status' => 1, 'token' => $token, 'request' => request()));
        }
        if (u_is_login()) {
            $rurl = get_redirect_url();
            if ($rurl && $rurl != '/') {
                return array('status' => 1, 'url' => $rurl);
            } else {
                return array('status' => 1, 'url' => Url('Admin/Index/rule'));
            }
        }
        return array('status' => 0, 'url' => 'shibai');
    }

    public function userList()
    {
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $User = new \app\api\model\User();
        if (request()->isGet()) {
            $arr = $User->getUserList(1,5);
            $Auth_group = db('Auth_group');
            $grouplist = $Auth_group->select();
            $arr['grouplist'] = $grouplist;
        } else {
            $page = input('param.page/d');
            $limit = input('param.limit/d');
            $arr = $User->getUserList($page, $limit);
        }
        $arr['status']=1;
        return json($arr);
    }

    //更该用户信息
    public function userUpdate()
    {
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        if (Request::instance()->isPost()) {
            $user = input('form/a');
            if($user['password']!=$user['password1']){
                return json(['msg'=>'密码不一致','status'=>0]);
            }
            $admin = getdata('user_auth' . request()->ip());
            $uid = $admin['account'];
            $now = time();
            $newuser['handler'] = $uid;
            $newuser['update_time'] = $now;
            $newuser['card_id']=$user['card_id'];
            $newuser['real_name']=$user['real_name'];
            $newuser['boss_id']=$user['boss_id'];
            $newuser['email']=$user['email'];
            $newuser['password']=$user['password'];
            //return json(array('status' => 'error', 'msg' => $user));
            Db::startTrans();
            try {
                Db::name('Auth_group_access')->where(array('uid' => $user['uid']))->update(array('group_id' => $user['group_id']));
                Db::name('User')->where(array('id' => $user['uid']))->update($newuser);
                Db::commit();
                return json(['msg' => 'success', 'status' => 1, 'editinfo' => ['editor' => $uid, 'edittime' => $now]]);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json(array('status' =>0, 'msg' => '保存失败！'));
            }
        }
    }
    //更改用户状态
    public function userStatusUp(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $User = new \app\api\model\User();
        if(Request::instance()->isPost()){
            $admin = getdata('user_auth' . request()->ip());
            $uid = $admin['account'];
            $now = time();
            $uparr['handler'] = $uid;//编辑人
            $uparr['update_time'] = $now;//修改时间
            $user=input('user/a');//获取客户端传过来的参数
            $uparr['status']=$user['ustatus']?0:1;
            $User->save($uparr,['id'=>$user['uid']]);
            return json(array('status'=>1,'msg'=>'更新成功！', 'editinfo' => ['editor' => $uid, 'edittime' => $now]));
        }
    }
    //添加用户
    public function userAdd(){
        //权限认证
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        if(Request::instance()->isPost()){
            $user=input('user/a');
            if(empty($user)){
                return array('status'=>'error','msg'=>'相关信息不能为空！');
            }
            if($user['account']==''){
                return array('status'=>'error','msg'=>'用户名不能为空！');
            }
            if($user['card_id']==''){
                return array('status'=>'error','msg'=>'工号不能为空！');
            }
            if($user['real_name']==''){
                return array('status'=>'error','msg'=>'真实姓名不能为空！');
            }
            if($user['password']==''){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            if($user['password']!=$user['password1']){
                return array('status'=>'error','msg'=>'密码不一致！');
            }
            if($user['group_id']==''||$user['group_id']=='0'){
                return array('status'=>'error','msg'=>'用户组不能为空！');
            }
            $User = new \app\api\model\User();
            $account=$User->where(array('account'=>$user['account']))->find();
            if($account){
                return array('status'=>'error','msg'=>'用户名已存在！');
            }
            $admin = getdata('user_auth' . request()->ip());
            $user['handler']=$admin['account'];
            $user['creat_time']=time();
            $data['group_id']=$user['group_id'];
            unset($user['password1']);
            unset($user['group_id']);
            Db::startTrans();
            try {
                $uid=Db::name('user')->insertGetId($user);
                if($uid){
                    $data['uid']=$uid;
                    if(Db::name('Auth_group_access')->insert($data)>0){
                        Db::commit();
                        $user['ustatus']=1;
                        $user['uid']=$uid;
                        return json(['msg' => 'success', 'status' => 1,'data'=>$user]);
                    }
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json(array('status' =>0, 'msg' => '保存失败！','bug'=>$e));
            }
        }
    }
    //判断是否有权限
    private function havePerm(){
        $isPerm=Session::get('name');
        return $isPerm;
    }
}