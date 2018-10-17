<?php
namespace models;

class admin extends model
{
    protected $table = 'admin';

    //  删除数据
    public function delete($id)
    {   
        $stmt = $this->_pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->_pdo->prepare("DELETE FROM admin_role WHERE admin_id = ?");
        $stmt->execute([$id]);
    }

    //  编辑，处理数据
    public function update($id)
    {
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set admin_name = ?,admin_password = ? WHERE id = ?");
        $stmt->execute([
            $_POST['username'],
            md5($_POST['password']),
            $id
        ]);
        //  删除之前所有数据
        $stmt = $this->_pdo->prepare("DELETE FROM admin_role WHERE admin_id = ?");
        $stmt->execute([$id]);
        $stmt = $this->_pdo->prepare("INSERT INTO admin_role(role_id,admin_id) VALUES(?,?)");
        foreach($_POST['role'] as $v)
        {
            $stmt->execute([
                $v,
                $id
            ]);
        }

    }

    //  编辑，取出数据
    public function findOne($id)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = $this->_pdo->prepare("SELECT role_id FROM admin_role WHERE admin_id = ?");
        $stmt->execute([$id]);
        $role = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //  将二维数组， 转化为一维数组
        $arr = [];
        foreach($role as $v)
        {
            $arr[] = $v['role_id'];
        }

        return [
            'admin'=>$admin,
            'role'=>$arr,
        ];
    }

    //  取出所有数据
    public function find()
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //  添加数据
    public function create()
    {
        // 插入管理员数据
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(admin_name,admin_password) VALUES(?,?)");
        $stmt->execute([
            $_POST['username'],
            md5($_POST['password'])
        ]);

        //  获取id
        $id = $this->_pdo->lastInsertId();
        //  添加管理与角色数据
        $stmt = $this->_pdo->prepare("INSERT INTO admin_role(role_id,admin_id) VALUES(?,?)");
        foreach($_POST['role'] as $v)
        {
            $stmt->execute([$v,$id]);
        }
    }
}