<?php
namespace models;

class article_category extends model
{
    protected $table = 'article_category';
    protected $fillable = ['cat_name'];

    //  取出所有分类
    public function findcate()
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}