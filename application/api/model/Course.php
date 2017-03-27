<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2017/2/16
 * Time: 下午 03:22
 */
namespace app\api\model;

use think\Model;

class Course extends Model
{
// 定义一对一关联
    public function sign()
    {
        return $this->hasMany('Sign');
    }

}