<?php
namespace controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class LoginController extends BaseController
{
    public function index()
    {
        view('login/login');
    }

    //  忘记密码
    public function noremember()
    {
        view('login/noremember');
    }

    //  验证用户是否注册过
    public function phone()
    {
        $user = new \models\user;
        $data = $user->phone($_POST['phone']);
        if($data)
        {
            $_SESSION['phone'] = $_POST['phone'];
            echo json_encode([
                'status'=>'200'     
            ]);
        }
    }

    //  短信验证码
    public function info()
    {
        $username = $_SESSION['phone'];
        //  短信验证
        $config = [
            'accessKeyId'    => 'LTAIU4H7g4bipz3o',
            'accessKeySecret' => 'LuVhkGvcGenM9b34MsCCXJujfI9otD',
        ];
        
        $rand = rand(100000, 999999);

        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($username);
        $sendSms->setSignName('itmuzi');
        $sendSms->setTemplateCode('SMS_147419883');
        $sendSms->setTemplateParam(['code' => $rand]);
        // $sendSms->setOutId('demo');

        //  将验证码保存到session中
        $_SESSION['code'] = $rand;
        $data = $client->execute($sendSms);
        echo json_encode([
            'message'=>$data->Message,
            'code'=>$data->Code
        ]);
    }

    //   ajax获取session数据
    public function code()
    {
        if(isset($_POST['code']) && $_POST['code'] == $_SESSION['phrase'])
        {
            echo json_encode([
                'status'=>'200'
            ]);
        }
    }

    //   发送验证码
    public function validate()
    {
        view('login/identity');
    }

    //  判断验证码
    public function password()
    {
        //  判断验证码是否正确
        if(isset($_POST['code']) && $_POST['code'] == $_SESSION['code'])
        {
            echo json_encode([
                'status'=>'200'
            ]);
            return ;
        }
        echo json_encode([
            'status'=>'404'
        ]);
    }

    //  忘记更新密码
    public function reset()
    {
        view('login/password');
    }

    public function resetPassword()
    {
        $user = new \models\user;
        $user->reset(md5($_POST['password']));
        redirect('/login/index');
    }

    //  修改密码
    public function edit()
    {
        view('login/edit');
    }

    //  处理密码
    public function doedit()
    {
        if(!isset($_SESSION['id']))
            die('非法访问');

        $user = new \models\user;
        $ret = $user->edit();
        if($ret)
            redirect('/login/edit');
    
        redirect('/login/index');
        
    }

    //   验证码图片
    public function url()
    {
        //  创建验证码 
        $builder = new CaptchaBuilder;
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        //  直接输入图片
        header('Content-type: image/jpeg');
        $builder->output();
    }

    //  登录
    public function login()
    {
        // 登录失败的次数存在 并且count大于登录3直接
        if(isset($_SESSION['count']) && $_SESSION['count'] > 3)
            die('您今天已经超过三次用户名或者密码错误，请明天登录');
        if(isset($_SESSION['time']) && $_SESSION['time'] <= time())
            die('您今天已经超过三次用户名或者密码错误，请明天登录');
        if(isset($_SESSION['time']) && $_SESSION['time'] > time())
            session_destroy();
        if(isset($_SESSION['phrase']) && $_SESSION['phrase'] != $_POST['captcha'])
            die('验证码输入错误');
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

    //    根据ip 判断是否  登录状态
    public function ip()
    {
        if(!isset($_SESSION['ip']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
        {
            echo json_encode([
                'ip'=>'null'
            ]);
        }
    }
    //   退出
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        $this->index();
    }

}