<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 10:55
 */

namespace app\admin\controller;
use think\Db;
use think\Request;
use app\admin\model\AuthGroup;
use app\admin\model\User;
use app\admin\model\AuthGroupAccess;
class Login extends \think\Controller
{
    //首页
    public function index(){
    }
    //登陆
    public function login(){
        if(Request::instance()->isAjax()){
            $user=input('user/a');
            if($user['account']==''){
                return array('status'=>'error','msg'=>'用户名不能为空！');
            }
            if($user['pwd']==''){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            $Admin=Db::name('Admin');
            $admin=$Admin->where(array('account'=>$user['account']))->find();
            if($admin==Null){
                return array('status'=>'error','msg'=>'用户名不存在！');
            }
            if($user['pwd']!=$admin['password']){
                return array('status'=>'error','msg'=>'密码错误！');
            }
            $time = $this->request->time();
            $auth = array('aid' => $admin['admin_id'], 'last_time' => $time,'account'=>$admin['account']);
            session('ke_user_auth', $auth);
            return array('status' => 1, 'url' => Url('Admin/Index/rule'));
        }
        if (is_login()) {
            $rurl=get_redirect_url();
            if ($rurl&&$rurl!='/') {
                redirect($rurl);
            }
            else {
                $this->redirect('Index/index');
            }
        }
        return view();
    }
    //登出
    public  function logout(){
        if(is_login()){
            session('ke_user_auth',null);
            $this->redirect('login');
        }
        else{
            $this->redirect('login');
        }
    }
    /**
     * [creatAccount 创建账号]
     * @return [type] [description]
     */
    public function creatAccount(){
        //权限认证
        define('SID', is_login());
        $auth=new \Auth\Auth();
        $request = Request::instance();
        if(!$auth->check($request->module().'-'.$request->controller().'-'.$request->action(),SID)){// 第一个参数是规则名称,第二个参数是用户UID
            /* return array('status'=>'error','msg'=>'有权限！');*/
            $this->error('你没有权限');
        }
        if(Request::instance()->isAjax()){
            $User=new User();
            $Auth_group_access=new AuthGroupAccess();
            $aid=is_admin();
            $Admin=Db::name('Admin');
            $isad=$Admin->where(array('admin_id'=>$aid))->find();
            if($isad==Null||$isad['account']!='admin'){
                return array('status'=>'error','msg'=>'您没有权限！');
            }
            $user=input('user/a');
            if(empty($user)){
                return array('status'=>'error','msg'=>'用户名密码不能为空！');
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
            if($user['password']!=$user['password1']){
                return array('status'=>'error','msg'=>'密码不一致！');
            }
            if($user['password']==''){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            if($user['group']==''||$user['group']=='0'){
                return array('status'=>'error','msg'=>'用户组不能为空！');
            }
            $account=$User->where(array('account'=>$user['account']))->find();
            if($account){
                return array('status'=>'error','msg'=>'用户名已存在！');
            }
            $user['handler']=$isad['account'];
            $user['creat_time']=time();
            $data['group_id']=$user['group'];
            unset($user['password1']);
            unset($user['group']);
            $User->data($user);
            $User->save();
            $uid=$User->id;
            if($uid){
                $data['uid']=$uid;
                if($Auth_group_access->data($data)->save()){
                    return array('status'=>'success','msg'=>'创建成功');
                }
            }
        }
        $Admin_Group=new AuthGroup();
        $group_list=$Admin_Group->select();
        return view('creatAccount',['grouplist'=>$group_list]);
    }
    //更改密码
    /**
     * [updateAccount 当前用户修改密码]
     * @return [type] [description]
     */
    public function updateAccount(){
        if(Request::instance()->isAjax()){
            $User=new User();
            $aid=is_admin();
            $acount=$User->where(array('id'=>$aid))->find();
            if($acount==Null){
                return array('status'=>'error','msg'=>'用户不存在！');
            }
            $user=input('user/a');
            if(empty($user)){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            if($user['opassword']==''){
                return array('status'=>'error','msg'=>'原密码不能为空！');
            }
            if($user['opassword']!=$acount['password']){
                return array('status'=>'error','msg'=>'原密码错误！');
            }
            if($user['npassword1']==''){
                return array('status'=>'error','msg'=>'新密码不能为空！');
            }
            if($user['npassword1']!=$user['npassword2']){
                return array('status'=>'error','msg'=>'新密码不一致！');
            }
            $data['password']=$user['npassword1'];
            $data['handler']=$acount['account'];
            $data['update_time']=time();
            $uid=$User->save($data,array('id'=>$aid));
            if($uid){
                return array('status'=>'success','msg'=>'修改成功！');
            }
            return array('status'=>'error','msg'=>'修改失败！');
        }
        return view();
    }
}