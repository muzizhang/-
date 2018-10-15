<?php
namespace models;

class userlog extends model
{
    protected $table = 'userlog';
    protected $fillable = ['path','user_id'];

    //  取出用户的登录数
    public function loginCount()
    {
        $stmt = $this->_pdo->prepare('SELECT user_id,path FROM {$this->table} GROUP BY user_id');
    }
}