<?php
namespace app\index\controller;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');
class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function indexapi()
    {

        $user=input('user/a');
        $username=$user['uname'];
        $pwd=$user['pwd'];
        $data = ['name'=>$username,'url'=>'thinkphp.cn','password'=>$pwd];
        $token=getdata('user_auth'.request()->ip());
        $token=$token['token'];
        return json(['data'=>$data,'code'=>1,'msg'=>$token,'request'=>request()->ip()]);

    }
    public function getdate(){
        $tabledate=[[
                    'date'=>'2016-05-02',
                    'name'=>'王小虎',
                    'address'=>'深圳市龙华富士康D13'
                ], [
                    'date'=>'2016-05-02',
                    'name'=>'王小狮',
                    'address'=>'深圳市龙华富士康富连网旗舰店'
                ], [
                    'date'=>'2016-05-02',
                    'name'=>'王小豹',
                    'address'=>'深圳市龙华富士康南二门'
                ], [
                    'date'=>'2016-05-02',
                    'name'=>'王小狐',
                    'address'=>'深圳市龙华富士康爱转角'
                ]];
        $date=array('subjects'=>$tabledate,'wo'=>'123');
        return json($date);
    }
    public function test(){
        $name=input('param.name');
        if (request()->isGet()){
        return json(['name'=>$name,'msg'=>'success','type'=>'get']);
        }
        if(request()->isPost()){
            return json(['name'=>$name,'msg'=>'success','type'=>'post']);
        }
    }
}
