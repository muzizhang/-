<?php
namespace models;

class user extends model
{
    protected $table = 'user';
    protected $fillable = ['password','username'];

    //  将数据插入到userlog表中
    public function userlog($getOS,$browser,$ip)
    {
        $stmt = $this->_pdo->prepare("INSERT INTO userlog(ip,equipment,browser,user_id) VALUES(?,?,?,?)");
        return $stmt->execute([
            $ip,
            $getOS,
            $browser,
            $_SESSION['id']
        ]);
    }

    //  注册
    public function register($password,$username)
    {
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->table}(password,username) VALUES(?,?)");
        $stmt->execute([
            $password,$username
        ]);
    }


    //  登录
    public function login($username,$password)
    {
        // 查找用户名是否存在
        $stmt = $this->_pdo->prepare("SELECT count(*) FROM {$this->table} WHERE username = ?");
        $stmt->execute([
            $username
        ]);
        $ret = $stmt->fetch(\PDO::FETCH_COLUMN);
        if($ret)
        {
            //  判断密码是否正确
            $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE username = ? AND password = ?");
            $stmt->execute([
                $username,$password
            ]);
            $ret1 = $stmt->fetch(\PDO::FETCH_ASSOC);
            if(!$ret1)
            {
                return false;
            }
            else
            {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $ret1['id'];
                return true;
            }
        }
        else
        {
            return false;
        }
    }
}