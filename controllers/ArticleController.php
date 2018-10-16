<?php
namespace controllers;

use Intervention\Image\ImageManagerStatic as Image;

class ArticleController extends BaseController
{
    public function index()
    {
        //  取出数据库的数据
        $article = new \models\article;
        $data = $article->findAll();
        //  取出所有分类
        $cate = new \models\category;
        $category = $cate->tree();

        view('article/index',[
            'data'=>$data,
            'category'=>$category,
        ]);
    }
    
    //   创建文章
    public function insert()
    {
        //  取出所有分类
        //  取出顶级
        $cate = new \models\category;
        $cat1 = $cate->cate_id();

        view('article/insert',[
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

    //  处理文章
    public function add()
    {
        $article = new \models\article;
        $article->create();
        redirect('/article/index');
    }

    //  编辑文章
    public function modify()
    {
        //  取出一级分类
        $cate = new \models\category;
        $cat1 = $cate->cate_id();

        $article = new \models\article;
        $data = $article->findArticle($_GET['id']);

        //  取出文章的图片
        $img = new \models\article_img;
        $img = $img->findImg($_GET['id']);

        view('article/modify',[
            'cat1'=>$cat1,
            'data'=>$data,
            'img'=>$img
        ]);
    }


    //  处理编辑
    public function edit()
    {
        $article = new \models\article;
        $article->edit($_GET['id']);
        redirect('/article/index');
    }

    //  删除文章
    public function delete()
    {
        $article = new \models\article;
        $article->delete($_GET['id']);
        redirect('/article/index');
    }
}