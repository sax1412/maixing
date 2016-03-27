<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class MemberController extends Controller
{
    public function member_add()
    {
        $name = I('name');
        $english = I('english');
        $position = I('position');
        $img = I('img');
        $intro = I('intro');
        $show = I('show');
        if ($name && $english && $position && $img && $intro) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['name'] = $name;
            $data['english'] = $english;
            $data['position'] = $position;
            $data['img'] = $img;
            $data['intro'] = $intro;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            $title = M('Member');
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

    public function member_list()
    {
        $res = D('Member')->order('id desc')->select();
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

    public function member_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Member')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function member_find()
    {
        $id = I('id');
        $res = D('Member')->where(['id' => $id])->find();
        if ($res) {
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function member_save()
    {
        $id = I('id');
        $name = I('name');
        $english = I('english');
        $position = I('position');
        $img = I('img');
        $intro = I('intro');
        $show = I('show');
        if ($name && $english && $position && $img && $intro) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            $data['name'] = $name;
            $data['english'] = $english;
            $data['position'] = $position;
            $data['img'] = $img;
            $data['intro'] = $intro;
            $data['time'] = time();
            if ($show == '是') {
                $data['show'] = 1;
            } else {
                $data['show'] = 0;
            }
            $res = D('Member')->where(['id' => $id])->save($data);
            if ($res) {
                json_ok();
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('内容不得为空');
        }
    }

    public function upload()
    {
        $upload = new \Think\Upload();
        $upload->maxSize   =     3145728 ;
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath  =     './Uploads/';
        $upload->savePath  =     '';
        // 上传文件
        $info   =   $upload->upload();
        $path='/Uploads/'.$info['upload']['savepath'].$info['upload']['savename'];
        if(!$info) {
            json_die('上传失败');
        }else{// 上传成功
            json_out_msg($path);
        }
    }
}