<?php 
session_start();
//  定义常量，根路径
define('ROOT',dirname(dirname(__FILE__)).'\\');
require(ROOT.'vendor/autoload.php');

//   实现类的自动加载
require(ROOT.'libs/function.php');

//   解析路由
/*  超全局变量   $_SERVER[]     
    $_SERVER['PATH_INFO']    //   地址栏的参数部分
*/
//  定义默认的控制器，方法名
$controller = '\controllers\IndexController';
$active = 'index';
if(isset($_SERVER['PATH_INFO']))
{
    //  将PATH_INFO  进行分割     将字符串变成数组
    $path = explode('/',$_SERVER['PATH_INFO']);
    $controller = '\controllers\\'.ucfirst($path[1]).'Controller';
    $active = $path[2];
}
$class = new $controller;
$class->$active();
