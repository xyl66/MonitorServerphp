<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2017/2/16
 * Time: 下午 03:50
 */
namespace app\index\model;

use think\Model;

class Blog extends Model
{
    protected $autoWriteTimestamp = true;
    protected $insert             = [
        'status' => 1,
    ];

    protected $field = [
        'id'          => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
        'name', 'title', 'content',
    ];
}