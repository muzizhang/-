<?php
namespace models;

class role extends model
{
    protected $table = 'role';

    //  删除
    public function delete($id)
    {
        $stmt = $this->_pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        // 删除权限
        $stmt = $this->_pdo->prepare("DELETE FROM role_privilage WHERE role_id = ?");
        $stmt->execute([$id]);
    }

    //  修改
    public function update($id)
    {
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set role_name = ? WHERE id = ?");
        $stmt->execute([$_POST['role_name'],$id]);
        //  修改权限
        $stmt = $this->_pdo->prepare("DELETE FROM role_privilage WHERE role_id = ?");
        $stmt->execute([$id]);
        $stmt = $this->_pdo->prepare("INSERT INTO role_privilage(pri_id,role_id) VALUES(?,?)");
        foreach($_POST['pri'] as $v)
        {
            $stmt->execute([
                $v,$id
            ]);
        }
    }

    //  修改数据
    public function findOne($id)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        //  取出对应的权限
        $stmt = $this->_pdo->prepare("SELECT pri_id FROM role_privilage WHERE role_id = ?");
        $stmt->execute([$id]);
        $pri = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //  将二维数据转换为一维数组
        $arr = [];
        foreach($pri as $v)
        {
            $arr[] = $v['pri_id'];
        }
        return [
            'data'=>$data,
            'pri'=>$arr
        ];
    }

    //  创建角色
    public function create()
    {
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(role_name) VALUES(?)");
        $stmt->execute([$_POST['role_name']]);
        // 创建角色的id
        $id = $this->_pdo->lastInsertId();
        //  创建角色对应的权限
        $stmt = $this->_pdo->prepare("INSERT INTO role_privilage(pri_id,role_id) VALUES(?,?)");
        foreach($_POST['pri'] as $v)
        {
            $stmt->execute([$v,$id]);
        }
    }

    //  取出角色，对应权限
    public function findRole()
    {
        $stmt = $this->_pdo->prepare("SELECT r.id,r.role_name,GROUP_CONCAT(e.pri_url) pri_url
                                        FROM {$this->table} r
                                        LEFT JOIN role_privilage p ON r.id = p.role_id
                                        LEFT JOIN privilage e ON p.pri_id = e.id
                                        GROUP BY r.role_name");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //  取出角色
    public function find()
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}