<?php
namespace controllers;

class PrivilageController extends BaseController
{
    public function index()
    {
        //  取出所有分类
        $pri = new \models\privilage;
        $data = $pri->find();
        view('privilage/index',[
            'data'=>$data,
        ]);
    }
    
    //   创建文章
    public function insert()
    {
        //  取出所有分类
        $pri = new \models\privilage;
        $data = $pri->find();
        view('privilage/insert',[
            'data'=>$data,
        ]);
    }

    //  处理文章
    public function add()
    {
        $pri = new \models\privilage;
        $pri->create();
        redirect('/privilage/index');
    }

    //  编辑文章
    public function modify()
    {
        //  取出所有分类
        $pri = new \models\privilage;
        $data = $pri->find();
        //  获取到原数据
        $pri = new \models\privilage;
        $pri = $pri->findOne($_GET['id']);
        view('privilage/modify',[
            'data'=>$data,
            'pri'=>$pri,
        ]);
    }


    //  处理编辑
    public function edit()
    {
        $pri = new \models\privilage;
        $pri->update($_GET['id']);
        redirect('/privilage/index');
    }

    //  删除权限
    public function delete()
    {
        $pri = new \models\privilage;
        $pri->delete($_GET['id']);
        redirect('/privilage/index');
    }
}