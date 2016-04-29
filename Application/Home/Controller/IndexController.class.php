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
            $list = D('Title')->where(['show' => 1, 'zn_en' => 1])->find();
        }
        json_out_msg($list);
    }

    public function city()
    {
        $str = $_SERVER['HTTP_USER_AGENT'];
        $status = I('status');
        if ($status) {
            $list = D('Contact')->where(['zn_en' => 0])->select();
        } else {
            $list = D('Contact')->where(['zn_en' => 1])->select();
        }
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            foreach ($list as &$v) {
                $v['agent'] = 1;
            }
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
                $time = date('Y/m/d', $v['time']);
                $time1 = explode('/', $time);
                $v['time1'] = $time1[0];
                $v['time2'] = $time1[1] . '/' . $time1[2];
            }
        } else {
            if ($status) {
                $list = D('News')->where(['zn_en' => 0])->select();
            } else {
                $list = D('News')->where(['zn_en' => 1])->select();
            }

            $list = array_values($list);
            foreach ($list as &$v) {
                $time = date('Y/m/d', $v['time']);
                $time1 = explode('/', $time);
                $v['time1'] = $time1[0];
                $v['time2'] = $time1[1] . '/' . $time1[2];
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
        $str = $_SERVER['HTTP_USER_AGENT'];
        $status = I('status');
        if ($status) {
            $list = D('Team')->where(['show' => 1, 'zn_en' => 0])->find();
        } else {
            $list = D('Team')->where(['show' => 1, 'zn_en' => 1])->find();
        }
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            $list['agent'] = 1;
        }
        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function member()
    {
        //$status = I('status');
        $list = D('Member')->where(['show' => 1])->select();
        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function abort()
    {
        $str = $_SERVER['HTTP_USER_AGENT'];
        $status = I('status');
        if ($status) {
            $list = D('Abort')->where(['show' => 1, 'zn_en' => 0])->find();
        } else {
            $list = D('Abort')->where(['show' => 1, 'zn_en' => 1])->find();
        }
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            $list['agent'] = 1;
        }
        json_out_msg($list);
    }

    public function menu()
    {
        $status = I('status');
        if ($status) {
            $list = D('Menu')->where(['show' => 1, 'zn_en' => 0])->select();
        } else {
            $list = D('Menu')->where(['show' => 1, 'zn_en' => 1])->select();
        }

        json_out_msg($list);
    }

    public function menu_detail()
    {
        $status = I('status');
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

    public function case1()
    {
        $str = $_SERVER['HTTP_USER_AGENT'];
        $status = I('status/d');
        if ($status == 1) {
            $list = D('Invest')->where(['show' => 1, 'zn_en' => 0])->select();
        } else {
            $list = D('Invest')->where(['show' => 1, 'zn_en' => 1])->select();
        }
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            $list['agent'] = 1;
        }
        json_out_msg($list);
    }

    function case_area()
    {
        $status = I('status/d');
        $res = D('Invest')->where(['zn_en' => $status])->distinct(true)->field('area')->select();
        json_out_msg($res);
    }

    public function case_find()
    {
        $w = I('w');
        switch ($w) {
            case '医疗':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 1])->select();
                break;
            case '消费':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 2])->select();
                break;
            case '高新技术':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 3])->select();
                break;
            case '前期':
                $list = D('Invest')->where(['zn_en' => 1, 'stage' => 1])->select();
                break;
            case '成长前期':
                $list = D('Invest')->where(['zn_en' => 1, 'stage' => 2])->select();
                break;
            case '成长期':
                $list = D('Invest')->where(['zn_en' => 1, 'stage' => 3])->select();
                break;
            case 'Medical care':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 1])->select();
                break;
            case 'Consumption':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 2])->select();
                break;
            case 'Hign technology':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 3])->select();
                break;
            case 'Early stage':
                $list = D('Invest')->where(['zn_en' => 0, 'stage' => 1])->select();
                break;
            case 'Early growth':
                $list = D('Invest')->where(['zn_en' => 0, 'stage' => 2])->select();
                break;
            case 'Growth':
                $list = D('Invest')->where(['zn_en' => 0, 'stage' => 3])->select();
                break;
        }
        if($list){

        }else{
            $list = D('Invest')->where(['area' => $w])->select();
        }
        $str = $_SERVER['HTTP_USER_AGENT'];
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            $list['agent'] = 1;
        }
        json_out_msg($list);
    }

    public function case_detail()
    {
        $id = I('id');
        $invest = D('Invest')->where(['id' => $id])->find();
        if ($invest['stage'] == 1) {
            $invest['stage'] = '早期';
        } elseif ($invest['stage'] == 2) {
            $invest['stage'] = '成长早期';
        } else {
            $invest['stage'] = '成长期';
        }
        if ($invest['exit'] == 0) {
            unset($invest['exit_time']);
            unset($invest['exit_way']);
            unset($invest['reward']);
            unset($invest['stock']);
            unset($invest['addr']);
        }
        json_out_msg($invest);
    }

    public function member_detail()
    {
        $id = I('id');
        $invest = D('Member')->where(['id' => $id])->find();
        json_out_msg($invest);
    }
}
