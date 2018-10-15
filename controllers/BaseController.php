<?php
namespace controllers;

class BaseController
{
    public function __construct()
    {
        $path = ['/register/index','/register/create','/login/index','/login/login','/login/logout'];
        //  判断如果PATH_INFO   的值   在其中的一个，则退出
        if(in_array(@$_SERVER['PATH_INFO'],$path))
            return ;

        $log = new \models\log;
        if(!isset($_SERVER['PATH_INFO']))
        {
            // 默认路由为  /
            $log->log();
        }
        $log->log(@$_SERVER['PATH_INFO']);
    }
}