<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class AbortController extends Controller
{
    public function abort_add()
    {
        $content = I('content');
        $show = I('show');
        if ($content) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['content'] = $content;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            $title = M('Abort');
            $res = $title->data($data)->add();
            if ($res) {
                json_ok();
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }

    }

    public function abort_list()
    {
        $res = D('Abort')->order('id desc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            $v['time'] = date('Y-m-d', $v['time']);
            if ($v['show'] == 1) {
                $v['show'] = '是';
            }else{
                $v['show'] = '否';
            }
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function abort_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Abort')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function abort_find()
    {
        $id = I('id');
        $res = D('Abort')->where(['id' => $id])->find();
        if ($res) {
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function abort_save()
    {
        $id = I('id');
        $content = I('content');
        $show = I('show');
        if ($content) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['content'] = $content;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            $res = D('Abort')->where(['id' => $id])->save($data);
            if ($res) {
                json_ok();
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }
    }
}