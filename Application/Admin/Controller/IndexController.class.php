<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        if ($_SESSION['name']) {
            redirect("/Public/admin/index.html");
        } else {
            redirect("/Public/admin/login.html");
        }

    }

    public function login()
    {
        $user = I('user');
        $password = I('password');
        if (!$user || !$password) {
            json_die('用户名或密码不能为空');
        }
        $map['user'] = $user;
        $map['password'] = $password;
        $res = D('Role')->where(['user' => $user])->find();
        if (!$res) {
            json_die('该用户不存在');
        } else {
            $result = D('Role')->where($map)->find();
            if ($result) {
                session('[start]');
                session('name', $user);
                json_ok();
            } else {
                json_die('密码错误');
            }
        }
    }

    public function role()
    {
        if ($_SESSION['name']) {
            $role = D('Role')->where(['user' => $_SESSION['name']])->find();
            $array['name'] = $_SESSION['name'];
            $array['auth'] = $role['auth'];
            json_out_msg($array);
        } else {
            json_die('400');
        }
    }
}
