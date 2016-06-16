<?php
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends Controller
{

    public function category_add()
    {
        $category = I('category');
        $defaults = I('defaults');
        $sort = I('sort/d');
        $zn = I('zn');
        $data = D('Category');
        $data->create();
        if ($zn == '中文') {
            $zn = 1;
        } else {
            $zn = 2;
        }
        if ($defaults == '是') {
            $defaults = 1;
            $list=D('Category')->where(['zn'=>$zn,'defaults'=>1])->find();
            if($list){
                json_die('已有缺省选项');
            }
        } else {
            $defaults = 2;
        }

        if ($category) {
            $data->sort = $sort;
            $data->defaults = $defaults;
            $data->zn = $zn;
            $data->admin = $_SESSION['name'];
            $res = $data->add();
            if ($res) {
                json_ok();
            } else {
                json_die('操作失败');
            }
        } else {
            json_die('内容不得为空!');
        }

    }

    public function category_list()
    {
        $res = D('Category')->order('id asc')->select();
        $res = array_values($res);
        foreach ($res as &$v) {
            if ($v['zn'] == 1) {
                $v['zn'] = '中文';
            } else {
                $v['zn'] = '英文';
            }
            if ($v['defaults'] == 1) {
                $v['defaults'] = '是';
            } else {
                $v['defaults'] = '否';
            }
        }
        $out['aaData'] = $res;
        echo json_encode($out);
    }

    public function category_delete()
    {
        $id = I('id');
        $id = explode(',', $id);
        $map['id'] = ['in', $id];
        $res = D('Category')->where($map)->delete();
        if ($res) {
            json_ok();
        } else {
            json_die('删除失败');
        }
    }

    public function category_find()
    {
        $id = I('id');
        $res = D('Category')->where(['id' => $id])->find();
        if ($res) {
            if ($res['zn'] == 1) {
                $res['zn'] = '中文';
            } else {
                $res['zn'] = '英文';
            }
            if ($res['defaults'] == 1) {
                $res['defaults'] = '是';
            } else {
                $res['defaults'] = '否';
            }

            json_out_msg($res);
        } else {
            json_die('未知错误');
        }
    }

    public function category_save()
    {
        $category = I('category');
        $defaults = I('defaults');
        $sort = I('sort/d');
        $zn = I('zn');
        $data = D('Category');
        $data->create();
        if ($zn == '中文') {
            $zn = 1;
        } else {
            $zn = 2;
        }
        if ($defaults == '是') {
            $defaults = 1;
            $list=D('Category')->where(['zn'=>$zn,'defaults'=>1])->find();
            if($list){
                json_die('已有缺省选项');
            }
        } else {
            $defaults = 2;
        }
        if ($category) {
            $data->sort = $sort;
            $data->defaults = $defaults;
            $data->zn = $zn;
            $data->ct = time();
            $data->admin = $_SESSION['name'];
            $res = $data->save();
            if ($res) {
                json_ok();
            } else {
                json_die('操作失败');
            }
        } else {
            json_die('内容不得为空!');
        }
    }

    public function lists()
    {
        $list = D('Category')->where(['zn' => 1])->select();
        $list = array_values($list);
        json_out_msg($list);
    }

    public function lists_en()
    {
        $list = D('Category')->where(['zn' => 2])->select();
        $list = array_values($list);
        json_out_msg($list);
    }
}
