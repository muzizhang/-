<?php
namespace models;

class userlog extends model
{
    protected $table = 'userlog';
    protected $fillable = ['path','user_id'];

    //  取出用户的登录数
    public function loginCount()
    {
        $stmt = $this->_pdo->prepare("SELECT u.username,count(DISTINCT l.id) c,l.user_id,g.path
                                        FROM userlog l
                                        LEFT JOIN user u ON u.id = l.user_id
                                        LEFT JOIN log g ON u.id = g.user_id
                                        WHERE to_days(l.created_at) = to_days(now())
                                        GROUP BY l.user_id,g.path;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}