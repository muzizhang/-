<?php
namespace controllers;

class TestController extends BaseController
{
    //    表单验证
    public function validate()
    {
        view('test/validate');
    }
    //  显示登录
    public function login()
    {
        view('test/suo');
    }
    //  处理  登录密码错误三次后不允许登录   ip
    public function nologin()
    {
        $ip = getIp();
        if($_SESSION['count'] < 3)
        {
            //  通过用户名取出数据
            $user = new \models\test;
            $data = $user->login();
            echo '<pre>';
            var_dump($data);

            //  判断密码存在session中    并且   保存在session中的密码 等于 用户传递的密码
            if(isset($_SESSION['password']) && $_SESSION['password'] == md5($data['password']))
            {
                //   成功，将  session中 的  OK    设置值为1
                $_SESSION['ok'] = 1;
                //   成功跳转
                redirect('/login/index');
            }
            //   如果 session中的 ok 不存在
            if(!isset($_SESSION['ok']))
            {
                //  累计叠加错误次数
                $_SESSION['count'] += 1;
                die;
            }

            
        }
        else
        {
            //  设置过期时间
            /*   setCookie
            *      参数：   
            *            cookie   name  名称
            *            cookie   value   值
            *            expire   过期时间
            *            path     有效路径   '/'    整个网站都有效
            */   
            setCookie(session_name(),session_id(),time()+1800,"/");   //  一个小时后过期
            die('您已经输错3次密码，请明天重试！');
        }
        
        var_dump(getIp());

        
    }
}