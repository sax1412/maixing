<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class MenuController extends Controller
{
    public function menu_add()
    {
        $menu = I('menu');
        $english = I('english');
        $title = I('title');
        $content = I('content');
        $en = I('en');
        $show = I('show');
        $zn_en = I('zn_en');
        $len = count($title);
        $str = "";
        if ($menu && $english ) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['menu'] = $menu;
            $data['english'] = $english;
            for ($i = 0; $i < $len; $i++) {
                $str .= $title[$i] . "&" . $content[$i] . "&" . $en[$i] . "*";
            }
            $data['time'] = time();
            $data['content'] = $str;
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
            $table = M('Menu');
            $res = $table->data($data)->add();
            if ($res) {
                //json_ok();
                json_out_msg($content);
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }

    }

    public function menu_list()
    {
        $res = D('Menu')->order('id desc')->select();
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

    public function menu_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Menu')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function menu_find()
    {
        $id = I('id');
        $res = D('Menu')->where(['id' => $id])->find();
        if ($res) {
            $content = $res['content'];
            $content = explode('*', $content);
            array_pop($content);
            foreach ($content as &$v) {
                $v = explode('&', $v);
            }
            $res['content'] = $content;
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }


    public function menu_save()
    {
        $id = I('id');
        $menu = I('menu');
        $english = I('english');
        $title = I('title');
        $content = I('content');
        $en = I('en');
        $show = I('show');
        $zn_en = I('zn_en');
        $len = count($title);
        $str = "";
        if ($title && $english) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['menu'] = $menu;
            $data['english'] = $english;
            for ($i = 0; $i < $len; $i++) {
                $str .= $title[$i] . "&" . $content[$i] . "&" . $en[$i] . "*";
            }
            $data['content'] = $str;
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
            $res = D('Menu')->where(['id' => $id])->save($data);
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