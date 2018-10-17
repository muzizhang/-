<?php
namespace models;

class privilage extends model
{
    protected $table = 'privilage';
    
    //  删除权限
    public function delete($id)
    {
        $stmt = $this->_pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);

        //  删除角色与权限的中间表的数据
        $stmt = $this->_pdo->prepare("DELETE FROM role_privilage WHERE pri_id = ?");
        $stmt->execute([$id]);
    }

    //更新权限
    public function update($id)
    {
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set pri_name=?,pri_url=?,parent_id=? WHERE id = ?");
        $stmt->execute([
            $_POST['pri_name'],
            $_POST['pri_url'],
            $_POST['privilage'],
            $id
        ]);
        //  删除角色与权限的中间表的数据
        $stmt = $this->_pdo->prepare("DELETE FROM role_privilage WHERE pri_id = ?");
        $stmt->execute([$id]);
    }

    //  编辑权限
    public function findOne($id)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //  创建权限
    public function create()
    {
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(pri_name,pri_url,parent_id) VALUES(?,?,?)");
        $stmt->execute([
            $_POST['pri_name'],
            $_POST['pri_url'],
            $_POST['privilage']
        ]);
    }
    
    //  取出所有分类
    public function find()
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->_tree($data);
    }
    public function _tree($data,$parent_id=0,$level=0)
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