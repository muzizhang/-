<?php
namespace models;

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
        if($_FILES['smallimg']['error'] == 0)
        {
            $stmt = $this->_pdo->prepare("SELECT logo FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            $logo = $stmt->fetch(\PDO::FETCH_ASSOC);
            //  删除原图片
            unlink(ROOT.'public/uploads'.$logo['logo']);

            //  接收图片
            $img = $_FILES['smallimg'];
            //   图片目录
            if(!is_dir(ROOT.'public/uploads/article/'.date('Ymd')))
            {
                mkdir(ROOT.'public/uploads/article/'.date('Ymd'),0777,true);
            }
            //  获取图片后缀
            $_ext = strrchr($img['name'],'.');
            //   图片名称
            $name = md5(time().rand(1,99999999));
            //   处理图片
            move_uploaded_file($img['tmp_name'],ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext);
            $value[] = '/article/'.date('Ymd').'/'.$name.$_ext;
            
            $stmt = $this->_pdo->prepare("UPDATE {$this->table} set cat1_id=?,cat2_id=?,cat3_id=?,title=?,author=?,content=?,logo=? WHERE id = {$id}");
            $stmt->execute($value);
        }
        else
        {
            //  编辑其他信息
            $stmt = $this->_pdo->prepare("UPDATE {$this->table} set cat1_id =?,cat2_id=?,cat3_id=?,title=?,author=?,content=? WHERE id = {$id}");
            $stmt->execute($value);
        }
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
        //  获取图片后缀
        $_ext = strrchr($img['name'],'.');
        //   图片名称
        $name = md5(time().rand(1,99999999));
        //   处理图片
        move_uploaded_file($img['tmp_name'],ROOT.'public/uploads/article/'.date('Ymd').'/'.$name.$_ext);
        // $_POST['cat_id'] = (int)$_POST['cat_id'];
        $_POST['logo'] = '/article/'.date('Ymd').'/'.$name.$_ext;
        $_POST['user_id'] = (int)$_SESSION['id'];
        static $data = [];
        foreach($_POST as $k=>$v)
        {
            $data[] = $v;
        }
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(cat1_id,cat2_id,cat3_id,title,author,content,logo,user_id) VALUES(?,?,?,?,?,?,?,?)");
        $stmt->execute($data);
    }

    public function delete($id)
    {
        //  取出该文章的图片路径
        $stmt = $this->_pdo->prepare("SELECT logo FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $logo = $stmt->fetch(\PDO::FETCH_ASSOC); 
        unlink(ROOT.'public/uploads'.$logo['logo']);
        $stmt = $this->_pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }
}