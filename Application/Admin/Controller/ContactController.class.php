<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class ContactController extends Controller
{
    public function contact_add()
    {
        $company = I('company');
        $tel = I('tel');
        $fax = I('fax');
        $img = I('img');
        $email = I('email');
        $city = I('city');
        $add = I('add');
        $zn_en = I('zn_en');
        if ($company && $tel && $fax && $img && $email && $add && $city) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            if ($zn_en == '中文') {
                $data['zn_en'] = 1;
            } else {
                $data['zn_en'] = 0;
            }
            $data['company'] = $company;
            $data['tel'] = $tel;
            $data['fax'] = $fax;
            $data['img'] = $img;
            $data['email'] = $email;
            $data['city'] = $city;
            $data['add'] = $add;
            $data['time'] = time();
            $title = M('Contact');
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

    public function contact_list()
    {
        $res = D('Contact')->order('id asc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            $v['time'] = date('Y-m-d', $v['time']);
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function contact_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Contact')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function contact_find()
    {
        $id = I('id');
        $res = D('Contact')->where(['id' => $id])->find();
        if ($res) {
            if ($res['show'] == 1) {
                $res['show'] = '是';
            } else {
                $res['show'] = '否';
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

    public function contact_save()
    {
        $id = I('id');
        $company = I('company');
        $tel = I('tel');
        $fax = I('fax');
        $img = I('img');
        $email = I('email');
        $city = I('city');
        $add = I('add');
        $zn_en = I('zn_en');
        if ($company && $tel && $fax && $img && $email && $add &&$city) {
            if (!$_SESSION['auth']) {
                redirect("/Public/admin/login.html");
            } else {
                $data['admin'] = $_SESSION['auth'];
            }
            if ($zn_en == '中文') {
                $data['zn_en'] = 1;
            } else {
                $data['zn_en'] = 0;
            }
            $data['company'] = $company;
            $data['tel'] = $tel;
            $data['fax'] = $fax;
            $data['img'] = $img;
            $data['email'] = $email;
            $data['city'] = $city;
            $data['add'] = $add;
            $data['time'] = time();
            $res = D('Contact')->where(['id' => $id])->save($data);
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
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Uploads/';
        $upload->savePath = '';
        // 上传文件
        $info = $upload->upload();
        $path = '/Uploads/' . $info['upload']['savepath'] . $info['upload']['savename'];
        if (!$info) {
            //json_die('上传失败');
            json_die($info);
        } else {// 上传成功
            json_out_msg($path);
        }
    }
}