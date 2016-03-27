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
        $list = D('Title')->where(['show' => 1])->find();
        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function city()
    {
        $list = D('Contact')->select();
        json_out_msg($list);
    }

    public function news()
    {
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
            $list = D('News')->select();
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
        $list = D('Team')->where(['show' => 1])->find();
        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function member()
    {
        $list = D('Member')->where(['show' => 1])->select();
        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function abort()
    {
        $list = D('Abort')->where(['show' => 1])->find();
        json_out_msg($list);
    }

    public function menu()
    {
        $list = D('Menu')->where(['show' => 1])->select();
        json_out_msg($list);
    }

    public function menu_detail()
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
}