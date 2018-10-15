<?php
namespace controllers;

// extends BaseController
class LogController 
{
    //    用户登录数和pv
    public function pageview()
    {
        // $userlog = new \models\
        view('log/pageview');
    }
    //   分析出当天用户访问的一个排行榜（top n）
    public function top()
    {
        view('log/top');
    }
}