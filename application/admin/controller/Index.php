<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\admin\model\User;
use think\Request;
use think\Db;
class Index extends \think\Controller
{
    // 这是THinkphp下的自动运行的方法，所有继承这个控制器的子类
    // 首先运行此方法
    public function _initialize(){
        define('SID', is_login());
        if (!SID) {
            set_redirect_url($_SERVER['REQUEST_URI']);
            $this->redirect('Login/login');
        }
        $user = session('ke_user_auth');
        if($user['account']!='admin'){
        //权限认证
         $auth=new \Auth\Auth();//权限认证Auth类
         $request = Request::instance();
         if(!$auth->check($request->module().'-'.$request->controller().'-'.$request->action(),SID)){// 第一个参数是规则名称,第二个参数是用户UID
             /* return array('status'=>'error','msg'=>'有权限！');*/
             $this->error('你没有权限');
         }
        }
    }
    //用户
    /**
     * [index 用户列表]
     * @return [type] [description]
     */
    public function index(){
        if(Request::instance()->isAjax()){
            $User=new User();
            $arr=$User->getUserList();
            return json($arr);
        }
        return view('user');
    }
    //权限
    /**
     * [rule 权限列表]
     * @return [type] [description]
     */
    public function rule(){
        if(Request::instance()->isAjax()){
            $Auth_rule=new AuthRule();
            $rulelist=$Auth_rule->getRuleList();
            return json($rulelist);
        }
        return view('rule');
    }
    //修改权限
    /**
     * [ruleUp 修改权限]
     * @return [type] [description]
     */
    public function ruleUp(){
        if(Request::instance()->isAjax()){
            $rule=input('post.rule/a');
            if(empty($rule['name'])){
                return array('status'=>'erro','msg'=>'更新失败,权方法名不能为空');
            };
            if(empty($rule['title'])){
               return array('status'=>'erro','msg'=>'更新失败,权限名不能为空');
            }
            $Auth_rule=new AuthRule();
            $result=$Auth_rule->update($rule);
            if($result===false){
                return array('status'=>'erro','msg'=>'更新失败');
            }
            return array('status'=>'success','msg'=>'更新成功');
        }
    }
    //启禁用权限
    /**
     * $rule['status'] 权限需要修改的最终状态（前端已处理过）
     * @param array $rule 权限信息数组
     * @return [type] [description]
     */
    public function ruleStatusUp(){
        $rule=input('rule/a');
        if($rule['status']==='false'){
            $rule['status']=0;
        }
        if($rule['status']==='true'){
            $rule['status']=1;
        }
        $Auth_rule=new AuthRule();
        $result=$Auth_rule->update($rule);
        if($result===false){
            return array('status'=>'erro','msg'=>'状态修改失败');
        }
        return array('status'=>'success','msg'=>'更新成功');
    }
    //添加权限
    public function ruleAdd(){
        if(Request::instance()->isAjax()){
            $rule=input('rule/a');
            if(empty($rule['name'])){
                return array('status'=>'error','msg'=>'添加失败,规则不能为空！');
            };
            if(empty($rule['title'])){
                return array('status'=>'error','msg'=>'添加失败,规则名不能为空！');
            }
            $Auth_rule=new AuthRule();
            $result=$Auth_rule->data($rule)->save();
            if($result){
                $rulelist=$Auth_rule->getRuleList();
                return array('status'=>'success','data'=>$rulelist);
            }
            return array('status'=>'erro','msg'=>'添加失败');

        }
    }

    //用户组
    public function group(){
        if(Request::instance()->isAjax()){
            $Auth_group=new AuthGroup();
            $arr=$Auth_group->getGroupList();
            return json($arr);
        }
        return view('group');
    }
    //修改用户组权限
    public function groupUp(){
        $Auth_group=new AuthGroup();
        $Auth_rule=new AuthRule();
        if(Request::instance()->isAjax()) {
            $group=input('group/a');//获取客户端传过来的参数
            $rulelist = $Auth_rule->select();//获取权限列表
            $newgroup['title']=$group['title'];
            $newgroup['id']=$group['id'];
            $str='';                            //权限字符串
            foreach ($group['rules'] as $u=>$v){//遍历获取权限字符串
                if($v=='1'||$v=='true'){
                    $str=$str.strval($rulelist[$u]['id']).',';
                }
            }
            $newgroup['rules']=$str;
           if($Auth_group->update($newgroup)) {
               return array('status'=>'success','msg'=>'更新成功！');
           }
            return array('status'=>'error','msg'=>'更新失败！');;
        }
    }
    //修改用户组状态
    /**
     * @param   $group['status'] 需要修改的最终状态（前端已处理过）
     * @return [type] [description]
     */
    public function groupstatusup(){
        $Auth_group=new AuthGroup();
        if(Request::instance()->isAjax()){
            $group=input('group/a');//获取客户端传过来的参数
            if($group['status']==='false'){
                $group['status']=0;
            }
            if($group['status']==='true'){
                $group['status']=1;
            }
            $Auth_group->allowField(['status'])->save($group,['id'=>$group['id']]);
            return array('status'=>'success','msg'=>'更新成功！');
        }

    }
    //添加用户组
    public function groupAdd(){
        $Auth_group=new AuthGroup();
        $Auth_rule=new AuthRule();
        if(Request::instance()->isAjax()) {
            $group=input('group/a');//获取客户端传过来的参数
            $rulelist = $Auth_rule->select();//获取权限列表
            $newgroup['title']=$group['title'];
            $str='';                            //权限字符串
            if(!array_key_exists('rules',$group)){
                return array('status'=>'error','msg'=>'权限不能为空！');
            }
            foreach ($group['rules'] as $u=>$v){//遍历获取权限字符串
                if($v=='1'||$v=='true'){
                    $str=$str.strval($rulelist[$u]['id']).',';
                }
            }
            $newgroup['rules']=$str;
            if($Auth_group->data($newgroup)->save()) {
                $arr=$Auth_group->getGroupList();//添加成功刷新页面
                return array('status'=>'success','data'=>$arr);
            }
            return array('status'=>'error','msg'=>'添加失败！');
        }
    }
    //删除用户组
    public function groupDelete(){
        if(Request::instance()->isAjax()) {
            $group = input('group/a');//获取客户端传过来的参数
            if(!empty($group['id'])){
                AuthGroup::destroy($group['id']);
                $Auth_group=new AuthGroup;
                $arr=$Auth_group->getGroupList();//添加成功刷新页面
                return array('status'=>'success','data'=>$arr);
            }
            else{
                return array('status'=>'error','msg'=>'删除失败，数据为空！');
            }
        }
    }

    //用户
    public function user(){
        if(Request::instance()->isAjax()){
            $User=new User();
            $arr=$User->getUserList();
            return json($arr);
        }
        return view('user');
    }
    //更该用户信息
    public function userUp(){
        if(Request::instance()->isAjax()){
            $user=input('user/a');
            $admin_user = session('ke_user_auth');
            Db::startTrans();
            try{
                Db::name('Auth_group_access')->where(array('uid'=>$user['uid']))->update(array('group_id'=>$user['group_id']));
                $data=array('password'=>$user['password'],'handler'=>$admin_user['account'],'update_time'=>time());
                Db::name('User')->where(array('id'=>$user['uid']))->update($data);
                Db::commit();
                $User=new User();
                $arr=$User->getUserList();
                return array('status'=>'success','data'=>$arr);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return array('status'=>'error','msg'=>'保存失败！');
            }
        }
    }
    public function userStatusUp(){
        $User=new User();
        if(Request::instance()->isAjax()){
            $user=input('user/a');//获取客户端传过来的参数
            if($user['ustatus']==='false'){
                $uparr['status']=0;
            }
            if($user['ustatus']==='true'){
                $uparr['status']=1;
            }
            $User->save($uparr,['id'=>$user['uid']]);
            return array('status'=>'success','msg'=>'更新成功！');
        }
    }
}
