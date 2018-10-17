<?php
namespace models;

class user extends model
{
    protected $table = 'user';
    protected $fillable = ['password','username'];

    //  忘记密码
    public function reset($password)
    {
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set password = ? WHERE username = ?");
        $stmt->execute([$password,$_SESSION['phone']]);
    }

    //   验证用户是否存在
    public function phone($phone)
    {
        $stmt = $this->_pdo->prepare("SELECT count(*) FROM {$this->table} WHERE username = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    //  修改密码
    public function edit()
    {
        //  根据session中的id  取出数据
        $stmt = $this->_pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$_SESSION['id']]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        //   判断原密码是否输入正确，如若正确，则更改密码
        if($data['password'] != md5($_POST['password']))
            return 1;
        
        $stmt = $this->_pdo->prepare("UPDATE {$this->table} set password = ? WHERE id = ?");
        $stmt->execute([
            md5($_POST['newpassword']),
            $_SESSION['id']
        ]);

        //  清空session
        session_destroy();
    }

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