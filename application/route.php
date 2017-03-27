<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('getdate','index/index/getdate');
Route::rule('indexapi','index/index/indexapi');
Route::rule(':version/user','api/:version.user/login');
Route::rule(':version/test','api/:version.monitor/addServer');
Route::rule(':version/getServerList','api/:version.monitor/getServerList');
Route::rule(':version/updateServer','api/:version.monitor/updateServer');
Route::rule(':version/updateServerStatus','api/:version.monitor/updateServerStatus');
Route::rule(':version/serviceApply','api/:version.monitor/serviceApply');
Route::rule(':version/getServerApplyList','api/:version.monitor/getServerApplyList');
Route::rule(':version/updateServerApply','api/:version.monitor/updateServerApply');
Route::rule(':version/updateServerApplyStatus','api/:version.monitor/updateServerApplyStatus');
Route::resource('blogs','index/blog');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
