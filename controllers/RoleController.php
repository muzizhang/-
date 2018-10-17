<?php
namespace controllers;

class RoleController extends BaseController
{
    public function index()
    {
        //  取出角色
        $role = new \models\role;
        $data = $role->findRole();
        view('role/index',[
            'data'=>$data
        ]);
    }
    
    //   创建角色
    public function insert()
    {
        //  取出所有权限
        $pri = new \models\privilage;
        $data = $pri->find();
        // echo '<pre>';
        // var_dump($data);
        view('role/insert',[
            'data'=>$data
        ]);
    }


    //  处理角色
    public function add()
    {
        $role = new \models\role;
        $role->create();
        redirect('/role/index');
    }

    //  编辑文章
    public function modify()
    {
        //  取出数据
        $role = new \models\role;
        $role = $role->findOne($_GET['id']);
        //  取出所有权限
        $pri = new \models\privilage;
        $data = $pri->find();
        
        view('role/modify',[
            'data'=>$data,
            'role'=>$role
        ]);
    }


    //  处理编辑
    public function edit()
    {
        $role = new \models\role;
        $role->update($_GET['id']);
        redirect('/role/index');
    }

    //  删除文章
    public function delete()
    {
        $role = new \models\role;
        $role->delete($_GET['id']);
    }
}