<?php
namespace app\api\model;

use think\Model;

class User extends Model
{
// 定义一对一关联
    public function sign1()
    {
        return $this->hasMany('Sign', 'user_id', 'account');
    }

    //定义公共获取用户方法
    function getUserList($page,$count)
    {
        $join = [
            ['think_auth_group_access w', 'a.id=w.uid'],
            ['think_auth_group c', 'w.group_id=c.id'],
        ];
        $userlist = $this->alias('a')->join($join)->field('*,a.status as ustatus')->select();
        $length=count($userlist);
        $userlist = $this->alias('a')->join($join)->field('*,a.status as ustatus')->page($page,$count)->select();
        $t = $this->getLastSql();
        $arr['userlist'] = $userlist;
        $arr['total'] = $length;
        return $arr;
    }

}