<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/16
 * Time: 16:30
 */

namespace Admin\Controller;

use Think\Controller;

class NewsController extends Controller
{

    public function __initialize()
    {
        if (!$_SESSION['auth']) {
            redirect("/Public/admin/login.html");
        }
    }
    public function news_add()
    {
        $title = I('title');
        $intro = I('intro');
        $content1 = I('content1');
        $content2 = I('content2');
        $content3 = I('content3');
        $img1 = I('img1');
        $img2 = I('img2');
        $img3 = I('img3');
        $auth = I('auth');
        $from = I('from');
        $link = I('link');
        $zn_en = I('zn_en');
        if ($title && $intro && $content1 && $auth && $from && $link) {
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
            $data['title'] = $title;
            $data['content1'] = $content1;
            $data['content2'] = $content2;
            $data['content3'] = $content3;
            $data['img1'] = $img1;
            $data['img2'] = $img2;
            $data['img3'] = $img3;
            $data['intro'] = $intro;
            $data['auth'] = $auth;
            $data['from'] = $from;
            $data['link'] = $link;
            $data['time'] = time();
            $title = M('News');
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

    public function news_list()
    {
        $res = D('News')->order('id asc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            $v['time'] = date('Y-m-d', $v['time']);
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function news_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('News')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function news_find()
    {
        $id = I('id');
        $res = D('News')->where(['id' => $id])->find();
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

    public function news_save()
    {
        $id = I('id');
        $title = I('title');
        $intro = I('intro');
        $content1 = I('content1');
        $content2 = I('content2');
        $content3 = I('content3');
        $img1 = I('img1');
        $img2 = I('img2');
        $img3 = I('img3');
        $auth = I('auth');
        $from = I('from');
        $link = I('link');
        $zn_en = I('zn_en');
        if ($title && $intro && $content1 && $auth && $from && $link) {
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
            $data['title'] = $title;
            $data['content1'] = $content1;
            $data['content2'] = $content2;
            $data['content3'] = $content3;
            $data['img1'] = $img1;
            $data['img2'] = $img2;
            $data['img3'] = $img3;
            $data['intro'] = $intro;
            $data['auth'] = $auth;
            $data['from'] = $from;
            $data['link'] = $link;
            $res = D('News')->where(['id' => $id])->save($data);
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