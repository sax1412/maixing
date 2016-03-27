<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        if($_SESSION['auth']){
            redirect("/Public/admin/index.html");
        }else{
            redirect("/Public/admin/login.html");
        }

    }

    public function login(){
        $user=I('user');
        $password=I('password');
        if(!$user || !$password){
            json_die('用户名或密码不能为空');
        }
        $map['user']=$user;
        $map['password']=$password;
        $res=D('Role')->where(['user'=>$user])->find();
        if(!$res){
            json_die('该用户不存在');
        }else{
            $result=D('Role')->where($map)->find();
            if($result){
                session('auth',$user);
                json_ok();
            }else{
                json_die('密码错误');
            }
        }
    }

    public function role(){
        json_out_msg($_SESSION['auth']);
    }
}