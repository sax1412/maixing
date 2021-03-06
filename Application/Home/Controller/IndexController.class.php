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
            $list = D('News')->where($map)->order('sort desc')->select();
            $list = array_values($list);
            foreach ($list as &$v) {
                //$time = date('Y/m/d', $v['time']);
                $v['time'] = str_replace('T', ' ', $v['time']);
                $time1 = explode('-', $v['time']);
                $v['time1'] = $time1[0];
                $v['time2'] = $time1[1] . '/' . $time1[2];
            }
        } else {
            if ($status) {
                $list = D('News')->where(['zn_en' => 0])->order('sort desc')->select();
            } else {
                $list = D('News')->where(['zn_en' => 1])->order('sort desc')->select();
            }

            $list = array_values($list);
            foreach ($list as &$v) {
                //$time = date('Y/m/d', $v['time']);
                $v['time'] = explode('T', $v['time']);
                $v['time'] = $v['time'][0];
                $time1 = explode('-', $v['time']);
                $v['time1'] = $time1[0];
                $v['time2'] = $time1[1] . '/' . $time1[2];
            }
        }
        json_out_msg($list);
    }

    public function news_detail()
    {
        $str = $_SERVER['HTTP_USER_AGENT'];
        $id = I('id/d');
        if ($id) {
            $list = D('News')->where(['id' => $id])->find();
            $list['time'] = str_replace('T', ' ', $list['time']);
            if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
                $list['agent'] = 1;
            }
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
        $status = I('status');
        $w = I('w');
        $category=D('Category')->where(['defaults'=>1,'zn'=>1])->find();
        $category_en=D('Category')->where(['defaults'=>1,'zn'=>2])->find();
        $map = [];
        if ($status == 1) {
            $map['show_en'] = 1;
            if ($w) {
                $map['category_en'] = $w;
            }else{
                $map['category_en'] = $category_en['category'];
            }
        } else {
            $map['show'] = 1;
            if ($w) {
                $map['category'] = $w;
            }else{
                $map['category'] = $category['category'];
            }
        }
        $list = D('Member')->where($map)->order('convert(name using gbk) asc')->select();

        //$list['content']="<pre>".$list['content']."</pre>";
        json_out_msg($list);
    }

    public function member_cate()
    {
        $status = I('status');
        if ($status ==1) {
            $list = D('Category')->where(['zn'=>2])->order('sort asc')->select();
        }else{
            $list = D('Category')->where(['zn'=>1])->order('sort asc')->select();
        }

        json_out_msg($list);
    }

    public function member_find()
    {
        $w = I('w');
        $list = D('Member')->where(['category' => $w])->select();
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
            //$list['agent'] = 1;
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
            case '生活':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 1, 'show' => 1])->select();
                break;
            case '服务':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 2, 'show' => 1])->select();
                break;
            case '科技':
                $list = D('Invest')->where(['zn_en' => 1, 'trade' => 3, 'show' => 1])->select();
                break;
            case 'Lifestyle':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 1, 'show' => 1])->select();
                break;
            case 'Service':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 2, 'show' => 1])->select();
                break;
            case 'Technology':
                $list = D('Invest')->where(['zn_en' => 0, 'trade' => 3, 'show' => 1])->select();
                break;
        }
        if ($list) {

        } else {
            $where['area'] = $w;
            $where['stage'] = $w;
            $where['_logic'] = 'OR';
            $map['_complex'] = $where;
            $map['show'] = 1;
            $list = D('Invest')->where($map)->select();
        }
        $str = $_SERVER['HTTP_USER_AGENT'];
        if (strstr($str, 'Android') || strstr($str, 'iPhone')) {
            //$list['agent'] = 1;
        }
        json_out_msg($list);
    }

    public function case_detail()
    {
        $id = I('id');
        $invest = D('Invest')->where(['id' => $id])->find();
        foreach ($invest as $key => &$value) {
            if (empty($value)) {
                unset($invest[$key]);
            }
        }
        if ($invest['exit'] == 0) {
            unset($invest['exit_time']);
            unset($invest['exit_way']);
            unset($invest['reward']);
            unset($invest['up_stock']);
            unset($invest['addr']);
        }
        if ($invest['exit'] == 1 && !strstr($invest['exit_way'], '上市')) {
            unset($invest['up_stock']);
            unset($invest['addr']);
        }
        if ($invest['trade'] == 1) {
            if ($invest['zn_en'] == 1) {
                $invest['trade'] = '生活';
            } else {
                $invest['trade'] = 'Lifestyle';
            }
        } elseif ($invest['trade'] == 2) {
            if ($invest['zn_en'] == 1) {
                $invest['trade'] = '服务';
            } else {
                $invest['trade'] = 'Service';
            }
        } else {
            if ($invest['zn_en'] == 1) {
                $invest['trade'] = '科技';
            } else {
                $invest['trade'] = 'Technology';
            }
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
