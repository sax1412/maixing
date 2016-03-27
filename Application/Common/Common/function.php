<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/15
 * Time: 14:57
 */
use Think\Log;

function json_ok() {
    echo json_encode(['code'=> 200, 'msg'=>'ok']);
}

function json_err($msg, $code=400) {
    http_response_code($code);
    echo json_encode(['code'=> $code, 'msg'=>$msg], JSON_UNESCAPED_UNICODE);
}

function json_not_pass($msg='请先登录') {
    http_response_code(401);
    die(json_encode(['code'=> 401, 'msg'=>$msg], JSON_UNESCAPED_UNICODE));
}

function json_die($msg, $code=400) {
    //http_response_code($code);
    die(json_encode(['code'=> $code, 'msg'=>$msg], JSON_UNESCAPED_UNICODE));
}

function json_out_msg($msg) {
    http_response_code(200);
    echo json_encode(['code'=> 200, 'msg'=>$msg], JSON_UNESCAPED_UNICODE);
}