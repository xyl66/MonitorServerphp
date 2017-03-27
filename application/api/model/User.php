<?php
namespace app\api\model;

use think\Model;

class User extends Model
{
// 定义一对一关联
public function sign1()
{
return $this->hasMany('Sign','user_id','account');
}

}