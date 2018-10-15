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

    public function top()
    {
        $stmt = $this->_pdo->prepare("SELECT count(*) c,path
                                        FROM {$this->table}
                                        WHERE to_days(created_at) = to_days(now())
                                        GROUP BY path
                                        ORDER BY c desc");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete($user_id)
    {
        $stmt = $this->_pdo->prepare("DELETE {$this->table},userlog
                                        FROM {$this->table} 
                                        LEFT JOIN userlog ON {$this->table}.user_id = userlog.user_id
                                        WHERE {$this->table}.user_id = ?");
        $stmt->execute([$user_id]);
    }
}