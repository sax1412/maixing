<?php
namespace Admin\Controller;

use Think\Controller;

class AuthController extends Controller
{
    public function add()
    {
        $admin = I('admin');
        $password = I('password');
        if ($admin && $password) {
            $data['user'] = $admin;
            $data['password'] = $password;
            $data['auth'] = 2;
            $res = D('Role')->data($data)->add();
            if ($res) {
                json_ok();
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }
    }

    public function auth_list()
    {
        $list = D('Role')->select();
        $list = array_values($list);
        foreach ($list as &$v) {
            if ($v['auth'] == 1) {
                $v['auth'] = '超级管理员';
            } else {
                $v['auth'] = '普通管理员';
            }
        }
        $out['aaData'] = $list;
        echo json_encode($out);
    }

    public function find()
    {
        $id = I('id');
        $res = D('Role')->where(['id' => $id])->find();
        if ($res) {
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function save()
    {
        $id = I('id');
        $admin = I('admin');
        $password = I('password');
        if ($admin && $password) {
            $data['user'] = $admin;
            $data['password'] = $password;
            $data['auth'] = 2;
            $res = D('Role')->where(['id' => $id])->save($data);
            if ($res) {
                json_ok();
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }
    }

    public function delete(){
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Role')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }
}
