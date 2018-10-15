<?php
namespace controllers;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class RegisterController extends BaseController
{
    public function index()
    {
        view('register/register');
    }
    //  短信验证
    public function sms()
    {
        $username = $_GET['phone'];
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

    public function email()
    {
        $username = $_GET['phone'];
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('smtp.126.com',25))     //  邮箱服务器
        ->setUsername('itmuzi@126.com')      //  邮箱用户名
        ->setPassword('xx1314')       //   邮箱密码，有的邮件服务器是授权码
        ;
        $rand = rand(100000, 999999);
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        //  截取
        $email = explode('@',$username);
        // Create a message
        $message = (new \Swift_Message('126邮箱'))      //  邮件标题
        ->setFrom(['itmuzi@126.com' => '全栈1班'])      //  发送者
        ->setTo([$username, $username => $email[0]])     //  发送对象，数组形式支持多个
        ->setBody('您的验证码为：'.$rand.',该验证码10分钟有效，请勿泄露于他人！')      //  邮件内容
        ;
        $_SESSION['code'] = $rand;
        // Send the message
        $result = $mailer->send($message);     //  发送    成功，返回1   true
        if($result)
        {
            echo json_encode([
                'message'=>'ok',
                'code'=>'ok'
            ]);
        }
        else
        {
            echo json_encode([
                'message'=>' ',
                'code'=>' '
            ]);
        }
    }


    public function create()
    {
        $user = new \models\user;
        if($_SESSION['code'] == $_POST['code'])
        {
            //  判断两次传入的密码是否一致
            $user->register(md5($_POST['password']),$_POST['username']);
            redirect('/login/index');
        }
        die('验证码输入错误！');
    }
}