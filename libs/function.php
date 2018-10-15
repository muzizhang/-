<?php
//  实现类的自动加载
function autoload($class)
{
    //  处理控制器路径
    $path = str_replace('\\','/',$class);
    //  引入控制器文件
    require(ROOT.$path.'.php');
}
//   注册类
spl_autoload_register('autoload');
//   视图
function view($file,$data=[])
{
    //  处理传递的参数
    //   数组变量化
    extract($data);
    include(ROOT.'views/'.$file.'.html');
}

//  页面跳转
function redirect($url)
{
    header('Location:'.$url);
    exit;
}

//  获取地址栏的参数
function GetUrlParams($except = [])
{
    //  删除循环变量
    foreach($except as $v)
    {
        unset($_GET[$v]);
    }

    $str = '';
    foreach($_GET as $k=>$v)
    {

        $str .= "$k={$v}&";
    }
    return $str;
}

//   获取用户的登录设备
function getOS()
{
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    
    if(strpos($agent, 'windows nt')) 
    {
        $platform = 'windows';
    }
    elseif(strpos($agent, 'macintosh'))
    {
        $platform = 'mac';
    }
    elseif(strpos($agent, 'ipod'))
    {
        $platform = 'ipod';
    } 
    elseif(strpos($agent, 'ipad')) 
    {
        $platform = 'ipad';
    } 
    elseif(strpos($agent, 'iphone')) 
    {
        $platform = 'iphone';
    } 
    elseif (strpos($agent, 'android')) 
    {
        $platform = 'android';
    } 
    elseif(strpos($agent, 'unix')) 
    {
        $platform = 'unix';
    } 
    elseif(strpos($agent, 'linux')) 
    {
        $platform = 'linux';
    } 
    else 
    {
        $platform = 'other';
    }
    
    return $platform;
}


//    获取用户访问使用的浏览器
function my_get_browser(){
    if(empty($_SERVER['HTTP_USER_AGENT'])){
     return 'robot！';
    }
    if( (false == strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident')!==FALSE) ){
     return 'Internet Explorer 11.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 10.0')){
     return 'Internet Explorer 10.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){
     return 'Internet Explorer 9.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){
     return 'Internet Explorer 8.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){
     return 'Internet Explorer 7.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){
     return 'Internet Explorer 6.0';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Edge')){
     return 'Edge';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
     return 'Firefox';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
     return 'Chrome';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){
     return 'Safari';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){
     return 'Opera';
    }
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){
     return '360SE';
    }
     //微信浏览器
    if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessage')){
     return 'MicroMessage';
    }
}



//   获取用户端的ip地址
function getIp(){ 
    $onlineip=''; 
    if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){ 
        $onlineip=getenv('HTTP_CLIENT_IP'); 
    } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){ 
        $onlineip=getenv('HTTP_X_FORWARDED_FOR'); 
    } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){ 
        $onlineip=getenv('REMOTE_ADDR'); 
    } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){ 
        $onlineip=$_SERVER['REMOTE_ADDR']; 
    } 
    return $onlineip; 
} 
