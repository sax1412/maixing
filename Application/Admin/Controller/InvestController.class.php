<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class InvestController extends Controller
{
    public function add()
    {
        $data = D('Invest');
        $data->create();
        $zn_en = I('zn_en');
        $exit = I('exit');
        if (!$_SESSION['auth']) {
            redirect("/Public/admin/login.html");
        } else {
            $data->admin = $_SESSION['auth'];
        }
        if ($zn_en == '中文') {
            $data->zn_en = 1;
        } else {
            $data->zn_en = 0;
        }
        if ($exit == '是') {
            $data->exit = 1;
        } else {
            $data->exit = 0;
        }
        $res = $data->add();
        if ($res) {
            json_ok();
        } else {
            json_die('未知错误');
        }
    }

    public function invest_list()
    {
        $res = D('Invest')->order('id asc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            if ($v['exit'] == 1) {
                $v['exit'] = '是';
            } else {
                $v['exit'] = '否';
            }
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Invest')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function find()
    {
        $id = I('id');
        $res = D('Invest')->where(['id' => $id])->find();
        if ($res) {
            if ($res['exit'] == 1) {
                $res['exit'] = '是';
            } else {
                $res['exit'] = '否';
            }
            if ($res['zn_en'] == 1) {
                $res['zn_en'] = '中文';
            } else {
                $res['zn_en'] = '英文';
            }
            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function save()
    {
        $data = D('Invest');
        $data->create();
        $zn_en = I('zn_en');
        $exit = I('exit');
        if (!$_SESSION['auth']) {
            redirect("/Public/admin/login.html");
        } else {
            $data->admin = $_SESSION['auth'];
        }
        if ($zn_en == '中文') {
            $data->zn_en = 1;
        } else {
            $data->zn_en = 0;
        }
        if ($exit == '是') {
            $data->exit = 1;
        } else {
            $data->exit = 0;
        }
        $res = $data->save();
        if ($res) {
            json_ok();
        } else {
            json_die('未知错误');
        }
    }

    public function upload()
    {
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Uploads/';
        $upload->savePath = '';
        // 上传文件
        $info = $upload->upload();
        $path = '/Uploads/' . $info['upload']['savepath'] . $info['upload']['savename'];
        if (!$info) {
            json_die('上传失败');
        } else {// 上传成功
            json_out_msg($path);
        }
    }
}