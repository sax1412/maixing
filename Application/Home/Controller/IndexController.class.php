<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        redirect('/Public/home/index.html');
    }

    public function home()
    {
        $status = I('status');
        if ($status == 1) {
            $list = D('Title')->where(['show' => 1, 'zn_en' => 0])->find();
        } else {
            $list = D('Title')->where(['show' => 1])->find();
        }
        json_out_msg($list);
    }

    public function city()
    {
        $status = I('status');
        if ($status) {
            $list = D('Contact')->where(['zn_en' => 0])->select();
        } else {
            $list = D('Contact')->select();
        }

        json_out_msg($list);
    }

    public function news()
    {
        $status = I('status');
        $word = I('word');
        $map['title'] = ['like', '%' . $word . '%'];
        $map['content'] = ['like', '%' . $word . '%'];
        $map['auth'] = ['like', '%' . $word . '%'];
        $map['intro'] = ['like', '%' . $word . '%'];
        $map['_logic'] = 'OR';
        if ($word) {
            $list = D('News')->where($map)->select();
            $list = array_values($list);
            foreach ($list as &$v) {
                $v['time'] = date('Y/m/d', $v['time']);
            }
        } else {
            if ($status) {
                $list = D('News')->where(['zn_en' => 0])->select();
            } else {
                $list = D('News')->select();
            }

            $list = array_values($list);
            foreach ($list as &$v) {
                $v['time'] = date('Y/m/d', $v['time']);
            }
        }
        json_out_msg($list);
    }

    public function news_detail()
    {
        $id = I('id/d');
        if ($id) {
            $list = D('News')->where(['id' => $id])->find();
            $list['time'] = date('Y/m/d H:i:s', $list['time']);
            if ($list) {
                json_out_msg($list);
            } else {
                json_die('未知错误');
            }
        } else {
            json_die('参数错误');
        }
    }

    public function team()
    {
        $status = I('status');
        if ($status) {
            $list = D('Team')->where(['show' => 1, 'zn_en' => 0])->find();
        } else {
            $list = D('Team')->where(['show' => 1])->find();
        }

        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function member()
    {
        $status = I('status');
        if ($status) {
            $list = D('Member')->where(['show' => 1, 'zn_en' => 0])->select();
        } else {
            $list = D('Member')->where(['show' => 1])->select();
        }

        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function abort()
    {
        $status = I('status');
        if ($status) {
            $list = D('Abort')->where(['show' => 1, 'zn_en' => 0])->find();
        } else {
            $list = D('Abort')->where(['show' => 1])->find();
        }

        json_out_msg($list);
    }

    public function menu()
    {
        $status = I('status');
        if ($status) {
            $list = D('Menu')->where(['show' => 1, 'zn_en' => 0])->select();
        } else {
            $list = D('Menu')->where(['show' => 1])->select();
        }

        json_out_msg($list);
    }

    public function menu_detail()
    {
        $status = I('status');
        $id = I('id');
        if ($status == 1) {
            $res = D('Menu')->where(['id' => $id, 'zn_en' => 0])->find();
        } else {
            $res = D('Menu')->where(['id' => $id])->find();
        }

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
}