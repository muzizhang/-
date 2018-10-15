<?php
namespace models;

class category extends model
{
    protected $table = 'category';
    protected $fillable = ['cate_name','parent_id','path'];

    //   取出顶级
    public function cate_id($parent_id = 0)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE parent_id = ?");
        $stmt->execute([$parent_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //  递归取出所有数据
    public function tree()
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->_tree($data);
    }
    //  参数
    /* 
        参数
            $data   所有的分类数据
            $parent_id    父级id
            $level      当前的层级
    */
    public function _tree($data,$parent_id = 0,$level = 0)
    {
        static $arr = [];
        foreach($data as $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                $v['level'] = $level;
                $arr[] = $v;
                $this->_tree($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }
}