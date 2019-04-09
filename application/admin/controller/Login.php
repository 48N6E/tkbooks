<?php

namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Db;
use think\Session;

Class Login extends Controller{

    public function index(){

        $admin_id = session('admin_name');
        if(!empty($admin_id)){
            $this->redirect('index/index');
        }

        $res = Db::table('books_module')->where('module_key','settings')->find();
        $data = json_decode($res['module_data'],true);
        $name = !empty($data['name']) ? $data['name'] : '' ;

        $this->view->name = $name;
        return $this->fetch('template/login');
    }

    public function login(){
        $admin_name = input("post.admin_name");
        $admin_password = input("post.admin_password");

        if(empty($admin_name) || empty($admin_password) ){
            return $this->error('用户名或密码不能为空');
        }

        $pwd = md5($admin_password);

        $has = Db::table('books_admin')->where('admin_name',$admin_name)->where('admin_password',$pwd)->find();
        if(!empty($has)){

            session('admin_id',$has['admin_id']);
            session('admin_name',$has['admin_name']);

            return $this->success('登陆成功','index/index');
        }else{
            return $this->error('管理名称或密码错误');
        }

    }

    public function loginout(){

       Session::delete('admin_id');
       Session::delete('admin_name');

       return alert_success('退出登陆成功','index');

    }


}