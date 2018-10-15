<?php
namespace controllers;


// use Flc\Alidayu\Client;
// use Flc\Alidayu\App;
// use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
// use Flc\Alidayu\Requests\IRequest;


use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class AlidayuController extends BaseController
{
    public function sms()
    {
        $config = [
            'accessKeyId'    => 'LTAIU4H7g4bipz3o',
            'accessKeySecret' => 'LuVhkGvcGenM9b34MsCCXJujfI9otD',
        ];
        
        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers('15035587991');
        $sendSms->setSignName('itmuzi');
        $sendSms->setTemplateCode('SMS_147419883');
        $sendSms->setTemplateParam(['code' => rand(100000, 999999)]);
        $sendSms->setOutId('demo');
        echo '<pre>';
        print_r($client->execute($sendSms));
    }


    public function email()
    {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('smtp.126.com',25))     //  邮箱服务器
        ->setUsername('itmuzi@126.com')      //  邮箱用户名
        ->setPassword('xx1314')       //   邮箱密码，有的邮件服务器是授权码
        ;
        
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message('126邮箱'))      //  邮件标题
        ->setFrom(['itmuzi@126.com' => 'muzi'])      //  发送者
        ->setTo(['itmuzi@aliyun.com', 'itmuzi@aliyun.com' => 'itmuzi'])     //  发送对象，数组形式支持多个
        ->setBody('Here is the message itself')      //  邮件内容
        ;

        // Send the message
        $result = $mailer->send($message);     //  发送    成功，返回1   true
    }
}
