<?php
namespace libs;


class Redis
{
    private static $redis = null;
    public $client;
    private function __clone() {}
    private function __construct()
    {
        $this->client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

    public static function Redis()
    {
        if(self::$redis == null)
        {
            self::$redis = new self;
        }
        return self::$redis;
    }
}