<?php
namespace models;

class article_img extends model
{
    protected $table = 'article_img';
    protected $fillable = ['url','article_id'];

    public function findImg($article_id)
    {
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE article_id = ?");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}