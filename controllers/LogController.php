<?php
namespace controllers;


class LogController extends BaseController
{
    //    用户登录数和pv
    public function pageview()
    {
        $userlog = new \models\userlog;
        $data = $userlog->loginCount();
        // echo '<pre>';
        // var_dump($data);
        // die;
        view('log/pageview',[
            'data'=>$data,
        ]);
    }
    //   分析出当天用户访问的一个排行榜（top n）
    public function top()
    {
        $log = new \models\log;
        $data = $log->top();
        view('log/top',[
            'data'=>$data,
        ]);
    }

    //  删除
    //   当删除的时候，只删除今天的登录次数和path数据
    public function delete()
    {
        $log = new \models\log;
        $log->delete($_GET['user_id']);
        redirect('/log/pageview');
    }
}