<?php
namespace app\api\controller\v1;
use app\api\model\ServiceApply;
use app\apilib\base;
use think\Request;
use think\Db;
use app\api\model\Deviceinfo;
use \think\Session;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');

class Monitor extends base
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
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function indexapi()
    {
        $data = ['name'=>'thinkphp','url'=>'thinkphp.cn'];
        return json(['data'=>$data,'code'=>1,'message'=>'操作完成']);
    }
    //获取服务器列表
    public function getServerList(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $user=getdata('user_auth'.request()->ip());
        $uid=$user['account'];
        $date=array('subjects'=>[],'total'=>0,'status'=>1);
        $table='deviceinfo';
        //return json(array('msg'=>$table,'status'=>1));
        if(request()->isGet()){
            $list=db($table)->where('creator',$uid)->select();
            $length=count($list);
            $date['total']=$length;
            $list=db($table)->page(1,5)->where('creator',$uid)->select();
        }
        else{
            $page=input('param.page/d');
            $limit=input('param.limit/d');
            $list=db($table)->page($page,$limit)->where('creator',$uid)->select();
        }
        foreach ($list as $key=>$value ){//处理查询结果
            $value['type']=strval($value['type']);
            $value['isValid']=$value['isValid']?true:false;
            $value['remarks']=explode(';',$value['remarks']);
            $remarks=['remark1'=>$value['remarks'][0],'remark2'=>$value['remarks'][1],'remark3'=>$value['remarks'][2],'remark4'=>$value['remarks'][3]];
            $value['remarks']=$remarks;
            if(strpos($value['disk'],'D')>0){
                $arr=explode(":",$value['disk']);
                $value['spaceD']=$arr[1];
                $value['disk']=$arr[0];
                }
            $list[$key]=$value;
        }
        $date['subjects']=$list;
        return json($date);
    }
    //添加服務器
    public function addServer(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $form=input('param.form/a');
        if(request()->isPost()){
            $user=getdata('user_auth'.request()->ip());
            $uid=$user['account'];
            $remarks=implode(';',$form['remarks']);
            $isValid=$form['isValid']=="true"?1:0;
            //return json(['msg'=>$uid,'status'=>0]);
            $dataarr=['ip'=>$form['ip'],'type'=>$form['type'],'size'=>$form['size'],'os'=>$form['os'],'disk'=>$form['disk'],'ram'=>$form['ram'],'cpuNum'=>$form['cpuNum'],'remarks'=>$remarks,'location'=>$form['location'],'sn_Description'=>$form['sn_Description'],'warranty'=>$form['warranty']=="NaN"?null:$form['warranty'],'ilo'=>$form['ilo'],'isValid'=>$isValid,'creator'=>$uid,'createDate'=>time()];
            $insertstr='insert into think_deviceinfo values (uuid(),:ip,:type,:size,:os,:disk,:ram,:cpuNum,:remarks,:location,:sn_Description,:warranty,:ilo,:isValid,:creator,:createDate,null,null)';
            $t=Db::execute($insertstr,$dataarr);
            if($t){
            return json(['msg'=>'success','status'=>1]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }

    //更改服务器信息
    public function updateServer(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $form=input('param.form/a');
        if(request()->isPost()){
            $user=getdata('user_auth'.request()->ip());
            $uid=$user['account'];
            $now=time();
            $remarks=implode(';',$form['remarks']);
            $isValid=$form['isValid']=="true"?1:0;
            $dataarr=['ip'=>$form['ip'],'type'=>$form['type'],'size'=>$form['size'],'os'=>$form['os'],'disk'=>$form['disk'],'ram'=>$form['ram'],'cpuNum'=>$form['cpuNum'],'remarks'=>$remarks,'location'=>$form['location'],'sn_Description'=>$form['sn_Description'],'warranty'=>$form['warranty']=="NaN"?null:$form['warranty'],'ilo'=>$form['ilo'],'isValid'=>$isValid,'modified'=>$uid,'modifyDate'=>$now];
            $deviceinfo = new Deviceinfo();
        // save方法第二个参数为更新条件
            $t=$deviceinfo->save($dataarr,['id' => $form['id']]);
            if($t){
                return json(['msg'=>'success','status'=>1,'editinfo'=>['editor'=>$uid,'edittime'=>$now]]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }
    //批量更改服务器状态
    public function updateServerStatus(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        if(request()->isPost()){
            $form=input('param.form/a');
            $status=input('param.status/d');
            $apply=input('param.apply/d');//获取apply true对应service_apply表 false对应deviceinfo表
            $server=new Deviceinfo();
            $list=[];
            foreach ($form as $key=>$value){
                array_push($list,['id'=>$value['id'],'isValid'=>$status]);
            }
            // 批量更新
            $t=$server->saveAll($list);
            if($t){
                return json(['msg'=>'success','status'=>1,'sqlreturn'=>$t]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }
    //服务器申请
    public function serviceApply(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $form=input('param.form/a');
        if(request()->isPost()){
            $list=db('service_apply')->where('sn_Description',$form['sn_Description'])->find();
            if($list!=null){
                return json(['msg'=>'計算機名已存在','status'=>0]);
            }
            $user=getdata('user_auth'.request()->ip());
            $uid=$user['account'];
            $remarks=implode(';',$form['remarks']);
            $isValid=1;
            $disk=strpos($form['disk'],'D')===false?$form['disk']:($form['disk'].':'.$form['spaceD']);
            //return json(['msg'=>$uid,'status'=>0]);
            //return json(['msg'=>($form['disk'].':'.$form['spaceD']),'status'=>1]);
            $dataarr=['ip'=>'VM','type'=>0,'size'=>'VM','os'=>$form['os'],'disk'=>$disk,'ram'=>$form['ram'],'cpuNum'=>$form['cpuNum'],'remarks'=>$remarks,'location'=>'VM','sn_Description'=>$form['sn_Description'],'warranty'=>$form['warranty']==""?null:$form['warranty'],'ilo'=>'VM','isValid'=>$isValid,'creator'=>$uid,'createDate'=>time()];
            $insertstr='insert into think_service_apply values (uuid(),:ip,:type,:size,:os,:disk,:ram,:cpuNum,:remarks,:location,:sn_Description,:warranty,:ilo,:isValid,:creator,:createDate,null,null)';
            $t=Db::execute($insertstr,$dataarr);
            if($t){
                return json(['msg'=>'success','status'=>1]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }
    //获取服务器申请列表
    public function getServerApplyList(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $user=getdata('user_auth'.request()->ip());
        $uid=$user['account'];
        $date=array('subjects'=>[],'total'=>0,'status'=>1);
        $table='service_apply';
        if(request()->isGet()){
            $list=db($table)->where('creator',$uid)->select();
            $length=count($list);
            $date['total']=$length;
            $list=db($table)->page(1,5)->where('creator',$uid)->select();
        }
        else{
            $page=input('param.page/d');
            $limit=input('param.limit/d');
            $list=db($table)->page($page,$limit)->where('creator',$uid)->select();
        }
        foreach ($list as $key=>$value ){//处理查询结果
            $value['type']=strval($value['type']);
            $value['isValid']=$value['isValid']?true:false;
            $value['remarks']=explode(';',$value['remarks']);
            $remarks=['remark1'=>$value['remarks'][0],'remark2'=>$value['remarks'][1],'remark3'=>$value['remarks'][2],'remark4'=>$value['remarks'][3]];
            $value['remarks']=$remarks;
            if(strpos($value['disk'],'D')>0){
                $arr=explode(":",$value['disk']);
                $value['spaceD']=$arr[1];
                $value['disk']=$arr[0];
            }
            $list[$key]=$value;
        }
        $date['subjects']=$list;
        return json($date);
    }
    //更改服务器申请信息
    public function updateServerApply(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        $form=input('param.form/a');
        if(request()->isPost()){
            $user=getdata('user_auth'.request()->ip());
            $uid=$user['account'];
            $now=time();
            $remarks=implode(';',$form['remarks']);
            $dataarr=['os'=>$form['os'],'disk'=>$form['disk'],'ram'=>$form['ram'],'cpuNum'=>$form['cpuNum'],'remarks'=>$remarks,'sn_Description'=>$form['sn_Description'],'modified'=>$uid,'modifyDate'=>$now];
            $serviceapply = new ServiceApply();
            // save方法第二个参数为更新条件
            $t=$serviceapply->save($dataarr,['id' => $form['id']]);
            if($t){
                return json(['msg'=>'success','status'=>1,'editinfo'=>['editor'=>$uid,'edittime'=>$now]]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }
    //批量更改服务器申请状态
    public function updateServerApplyStatus(){
        if(!$this->havePerm())
            return json(['msg'=>'抱歉，您沒有權限','status'=>0]);
        if(request()->isPost()){
            $form=input('param.form/a');
            $server=new ServiceApply();
            if(input('param.status/d')){
                $status=input('param.status/d');
                $list=[];
                foreach ($form as $key=>$value){
                    array_push($list,['id'=>$value['id'],'isValid'=>$status]);
                }
                // 批量更新
                $t=$server->saveAll($list);
            }
            else{
                $form['isValid']=$form['isValid']==="false"?1:0;
                $t=$server->save([
                    'isValid'  => $form['isValid']
                ],['id' => $form['id']]);
            }
            if($t){
                return json(['msg'=>'success','status'=>1,'sqlreturn'=>$t]);
            }
            else
                return json(['msg'=>'erro','status'=>0]);
        }
    }
    //判断是否有权限
    private function havePerm(){
        $isPerm=Session::get('name');
        return $isPerm;
    }
}
