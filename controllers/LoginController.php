<?php
namespace controllers;

class LoginController extends BaseController
{
    public function index()
    {
        view('login/login');
    }

    public function login()
    {
        // 登录失败的次数存在 并且count大于登录3直接
        if(isset($_SESSION['count']) && $_SESSION['count'] > 3)
            die('您今天已经超过三次用户名或者密码错误，请明天登录');
        if(isset($_SESSION['time']) && $_SESSION['time'] <= time())
            die('您今天已经超过三次用户名或者密码错误，请明天登录');
        if(isset($_SESSION['time']) && $_SESSION['time'] > time())
            session_destroy();
        if(isset($_SESSION['code']) && $_SESSION['code'] != $_POST['code'])
            die('验证码输入错误，请重新输入');

        //  根据接收的username  去数据库中进行查找
        $user = new \models\user;  
        $data = $user->login($_POST['username'],md5($_POST['password']));
        if(!$data)
        {
            if(!isset($_SESSION['count']))
            {
                $_SESSION['count'] = 0;
            }
            $_SESSION['count'] += 1;
            if($_SESSION['count'] >= 3)
            {
                //  设置session过期时间
                $time = time()+86400;
                $_SESSION['time'] = $time;
            }
            die("用户名或者密码错误！");
        }

        //   当用户登录成功之后，将数据保存到userlog表
        //  设备
        $getOS = getOS();
        $browser = my_get_browser();
        $ip = getIp();
        $ret = $user->userlog($getOS,$browser,$ip);
        $_SESSION['count'] = 0;
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        redirect('/index/index');
    }

    public function ip()
    {
        if(isset($_SESSION['ip']) && $_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
        {
            echo json_encode([
                'ip'=>''
            ]);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        $this->index();
    }

}