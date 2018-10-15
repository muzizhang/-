<?php
namespace models;


class log extends model
{
    protected $table = 'log';
    protected $fillable = ['path','user_id'];

    public function log($path = '/')
    {
        //  判断session中的id  是否存在
        $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(path,user_id) VALUES(?,?)");
        $stmt->execute([
            $path,$id
        ]);
    }
}