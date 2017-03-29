/**
 * Created by F3233253 on 2016/11/16.
 */
new Vue({
    el:"#creatAccount",
    data:{
        user:{
            account:"",
            card_id:"",
            real_name:"",
            boss_id:"",
            email:"",
            password:"",
            password1:"",
            group:"",
        },
        warn:{
            name:false,
            pwd:false,
            cardid:false,
            realname:false,
            pwd1:false,
        },
    },
    methods:{
        check_null:function(event){
            this.warn.name=this.user.account.length<=0?true:false;
            this.warn.cardid=this.user.cardid.length<=0?true:false;
            this.warn.realname=this.user.realname.length<=0?true:false;
            this.warn.pwd=this.user.password.length<=0?true:false;
            this.warn.pwd1=this.user.password!=this.user.password1?true:false;
            var user=this.user;
            if(!this.warn.name&&!this.warn.pwd&&!this.warn.cardid&&!this.warn.realname&&!this.warn.pwd1){
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: creatAccountURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'user': user,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">创建成功！</p>');
                            }
                            else{
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">创建失败：'+result.msg+'</p>');
                            }
                        }).fail(function () {
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                        });
                    },
                    buttons: {
                        '确定': {
                            btnClass: 'btn-info',
                            action: function () {
                                $("button[class='close']").click();
                            }
                        },
                    }
                });
            }
        },
    }
})