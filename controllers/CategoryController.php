<?php
namespace controllers;

class CategoryController
{
    public function index()
    {
        //  取出所有数据
        $cate = new \models\category;
        $category = $cate->tree();
        view('category/index',[
            'category'=>$category
        ]);
    }

    public function insert()
    {
        $cate = new \models\category;
        $cat1 = $cate->cate_id();
        view('category/insert',[
            'cat1'=>$cat1,
        ]);
    }

    //  取出子级
    public function cate_id()
    {
        $cate = new \models\category;
        $data = $cate->cate_id($_GET['parent_id']);
        echo json_encode($data);
    }

    public function add()
    {
        
    }

    public function modify()
    {
        $cate = new \models\category;
        //  取出一级分类
        $cat1 = $cate->cate_id();
        //  取出二级分类
        //  取出三级分类
        view('category/modify');
    }
}