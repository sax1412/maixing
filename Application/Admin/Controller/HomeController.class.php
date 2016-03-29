<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class HomeController extends Controller
{
    public function banner_add()
    {
        $title = I('title');
        $note = I('note');
        $content = I('content');
        $show = I('show');
        $zn_en = I('zn_en');
        if ($title && $note && $content) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['title'] = $title;
            $data['content'] = $content;
            $data['note'] = $note;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            if ($zn_en == '中文') {
                $data['zn_en'] = 1;
            } else {
                $data['zn_en'] = 0;
            }
            $title = M('Title');
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

    public function banner_list()
    {
        $res = D('Title')->order('id asc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            $v['time'] = date('Y-m-d', $v['time']);
            if ($v['show'] == 1) {
                $v['show'] = '是';
            } else {
                $v['show'] = '否';
            }
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function banner_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Title')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function banner_find()
    {
        $id = I('id');
        $res = D('Title')->where(['id' => $id])->find();
        if ($res) {
            if ($res['show'] == 1) {
                $res['show'] = '是';
            } else {
                $res['show'] = '否';
            }
            if ($res['zn_en'] == 1) {
                $res['zn_en'] = '是';
            } else {
                $res['zn_en'] = '否';
            }
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function banner_save()
    {
        $id = I('id');
        $title = I('title');
        $note = I('note');
        $content = I('content');
        $show = I('show');
        $zn_en = I('zn_en');
        if ($title && $note && $content) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['title'] = $title;
            $data['note'] = $note;
            $data['content'] = $content;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            if ($zn_en == '中文') {
                $data['zn_en'] = 1;
            } else {
                $data['zn_en'] = 0;
            }
            $res = D('Title')->where(['id' => $id])->save($data);
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