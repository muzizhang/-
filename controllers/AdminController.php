<?php
namespace controllers;

class AdminController extends BaseController
{
    public function index()
    {
        //  取出数据
        $admin = new \models\admin;
        $data = $admin->find();
        view('admin/index',[
            'data'=>$data,
        ]);
    }
    
    //   创建管理员
    public function insert()
    {
        //  取出角色
        $role = new \models\role;
        $data = $role->find();
        view('admin/insert',[
            'data'=>$data
        ]);
    }

    //  处理文章
    public function add()
    {
        $admin = new \models\admin;
        $admin->create();
        redirect('/admin/index');
    }

    //  编辑文章
    public function modify()
    {
        //   取出一条数据
        $admin = new \models\admin;
        $man = $admin->findOne($_GET['id']);
        //  取出角色
        $role = new \models\role;
        $data = $role->find();
        view('admin/modify',[
            'data'=>$data,
            'man'=>$man,
        ]);
    }


    //  处理编辑
    public function edit()
    {
        //   更新数据
        $admin = new \models\admin;
        $admin->update($_GET['id']);
        redirect('/admin/index');
    }

    //  删除管理员
    public function delete()
    {
        $admin = new \models\admin;
        $admin->delete($_GET['id']);
        redirect('/admin/index');
    }
}