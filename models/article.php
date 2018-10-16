<?php
namespace models;

use Intervention\Image\ImageManagerStatic as Image;

class article extends model
{
    protected $table = 'article';
    protected $fillable = ['title','content','user_id'];

    //  编辑
    public function edit($id)
    {
        static $value = [];
        foreach($_POST as $k=>$v)
        {
            $value[] = $v;
        }
        //   判断一下 图片是否有修改
        $stmt = $this->_pdo->prepare("SELECT * FROM article_img WHERE article_id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $stmt = $this->_pdo->prepare("DELETE FROM article_img WHERE article_id = ? AND id = ?");
        
        $stmt1 = $this->_pdo->prepare("INSERT INTO article_img(id,url,big_url,middle_url,small_url,article_id) VALUES(?,?,?,?,?,?)");

        //   循环上传的图片信息
        foreach($_FILES['smallimg']['name'] as $k=>$v)
        {   
            //   判断图片是否修改，如若修改则为 error  == 0
            if($_FILES['smallimg']['error'][$k] == 0)
            {
                //  循环数据取出的数据，删除相应的数据
                foreach($data as $a=>$b)
                {
                    if($k == $a)
                    {
                        //  删除数据
                        @unlink(ROOT.'public/uploads'.$b['url']);
                        @unlink(ROOT.'public/uploads'.$b['big_url']);
                        @unlink(ROOT.'public/uploads'.$b['middle_url']);
                        @unlink(ROOT.'public/uploads'.$b['small_url']);
                        // 删除数据库中的数据
                        $stmt->execute([$id,$b['id']]);
                        //   拼出每张图片并且上传成功才处理图片
                        //  获取图片后缀
                        $_ext = strrchr($v,'.');
                        //   图片名称
                        $name = md5(time().rand(1,99999999));
                        //   处理图片
                        move_uploaded_file($_FILES['smallimg']['tmp_name'][$k],ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext);
                        $bigimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(650,650)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/big_'.$name.$_ext);
                        $middleimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(350,350)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/middle_'.$name.$_ext);
                        $smallimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(150,150)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/small_'.$name.$_ext);
                        $stmt1->execute([$b['id'],"/article/".date('Ymd')."/".$name.$_ext,'/article/'.date('Ymd').'/big_'.$name.$_ext,'/article/'.date('Ymd').'/middle_'.$name.$_ext,'/article/'.date('Ymd').'/small_'.$name.$_ext,$id]);   
                    }
                    if($k > $a)
                    {
                        //   拼出每张图片并且上传成功才处理图片
                        //  获取图片后缀
                        $_ext = strrchr($v,'.');
                        //   图片名称
                        $name = md5(time().rand(1,99999999));
                        //   处理图片
                        move_uploaded_file($_FILES['smallimg']['tmp_name'][$k],ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext);
                        $bigimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(650,650)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/big_'.$name.$_ext);
                        $middleimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(350,350)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/middle_'.$name.$_ext);
                        $smallimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                                        ->resize(150,150)
                                        ->save(ROOT.'public/uploads/article/'.date('Ymd').'/small_'.$name.$_ext);
                        $stmt1->execute([null,"/article/".date('Ymd')."/".$name.$_ext,'/article/'.date('Ymd').'/big_'.$name.$_ext,'/article/'.date('Ymd').'/middle_'.$name.$_ext,'/article/'.date('Ymd').'/small_'.$name.$_ext,$id]);   
                    }
                    break;
                }
            }
        }  
        //  编辑其他信息
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set cat1_id =?,cat2_id=?,cat3_id=?,title=?,author=?,content=? WHERE id = {$id}");
        $stmt->execute($value);
    }

    //  取出一条数据
    public function findArticle($id)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //  取出所有数据
    public function findAll()
    {
        $where = 1;
        $data = [];
        //  获取地址栏信息
        //  标题，内容
        if(isset($_GET['keyword']) && $_GET['keyword'])
        {
            $where .= " and (title like ? OR content like ?)";
            $data[] = '%'.$_GET['keyword'].'%'; 
            $data[] = '%'.$_GET['keyword'].'%'; 
        }
        //  分类
        if(isset($_GET['search-sort']) && $_GET['search-sort'])
        {
            $where .= " and cat_id = ?";
            $data[] = $_GET['search-sort'];
        }
        //  排序
        $odby = 'created_at';
        $odway = 'desc';
        if(isset($_GET['odby']) && $_GET['odby'] == 'updated_at')
        {
            $odby = 'updated_at';
        }

        if(isset($_GET['odway']) && $_GET['odway'] == 'asc')
        {
            $odway = 'asc';
        }


        //  分页
        //  每页显示多少条
        $page = 20;
        //  接收当前页码
        $p = isset($_GET['p']) ? max(1,(int)$_GET['p']) : 1;
        //   一共有多少条数据
        $stmt = $this->_pdo->prepare("SELECT count(*) FROM {$this->table} WHERE $where");
        $stmt->execute($data);
        $count = $stmt->fetch(\PDO::FETCH_COLUMN);
        //  总共可以分为几页
        $pageNum = ceil($count/$page);

        //  计算每页从第几条开始显示
        $offset = ($p-1)*$page;

        $btn = '';
        for($i=1;$i<=$pageNum;$i++)
        {   
            $params = GetUrlParams(['p']);
            $btn .= "<a href='?{$params}p=$i'>".$i."</a>";
        }

        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE $where ORDER BY $odby $odway LIMIT $offset,$page");
        $stmt->execute($data);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return [
            'data'=>$data,
            'btn'=>$btn
        ];
    }

    //  添加数据
    public function create()
    {
        //  接收图片
        $img = $_FILES['smallimg'];

        //   图片目录
        if(!is_dir(ROOT.'public/uploads/article/'.date('Ymd')))
        {
            mkdir(ROOT.'public/uploads/article/'.date('Ymd'),0777,true);
        }
        
        $_POST['user_id'] = (int)$_SESSION['id'];
        static $data = [];
        foreach($_POST as $k=>$v)
        {
            $data[] = $v;
        }
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(cat1_id,cat2_id,cat3_id,title,author,content,user_id) VALUES(?,?,?,?,?,?,?)");
        $stmt->execute($data);
        //  文章id
        $article_id = $this->_pdo->lastInsertId();
        $stmt = $this->_pdo->prepare("INSERT INTO article_img(url,big_url,middle_url,small_url,article_id) VALUES(?,?,?,?,?)");
        foreach($img['name'] as $k=>$v)
        {
            //  获取图片后缀
            $_ext = strrchr($v,'.');
            //   图片名称
            $name = md5(time().rand(1,99999999));
            //   处理图片
            move_uploaded_file($img['tmp_name'][$k],ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext);
            $bigimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                            ->resize(650,650)
                            ->save(ROOT.'public/uploads/article/'.date('Ymd').'/big_'.$name.$_ext);
            $middleimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                            ->resize(350,350)
                            ->save(ROOT.'public/uploads/article/'.date('Ymd').'/middle_'.$name.$_ext);
            $smallimg = Image::make(ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext)
                            ->resize(150,150)
                            ->save(ROOT.'public/uploads/article/'.date('Ymd').'/small_'.$name.$_ext);
            $stmt->execute(['/article/'.date('Ymd').'/'.$name.$_ext,'/article/'.date('Ymd').'/big_'.$name.$_ext,'/article/'.date('Ymd').'/middle_'.$name.$_ext,'/article/'.date('Ymd').'/small_'.$name.$_ext,$article_id]);
        }
    }

    public function delete($id)
    {
        //  取出该文章的图片路径
        $stmt = $this->_pdo->prepare("SELECT * FROM article_img WHERE article_id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($data as $k=>$v)
        {
            unlink(ROOT.'public/uploads'.$v['url']);
            unlink(ROOT.'public/uploads'.$v['big_url']);
            unlink(ROOT.'public/uploads'.$v['middle_url']);
            unlink(ROOT.'public/uploads'.$v['small_url']);
        }
        //  删除图片数据
        $stmt = $this->_pdo->prepare("DELETE FROM article_img WHERE article_id = ?");
        $stmt->execute([$id]);
        //  删除文章
        $stmt = $this->_pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }
}