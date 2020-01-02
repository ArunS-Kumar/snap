<?php

namespace App\Services;

class LogDNAService
{

    private static $instance = null;
    private $logger = null;

    private function __construct()
    {
        $host = \App::runningInConsole()?config('app.url'):$_SERVER['HTTP_HOST'];
        $this->logger = new \Monolog\Logger('general');
        $logdnaHandler = new \Zwijn\Monolog\Handler\LogdnaHandler(config('app.logdna_ingestion_key'), $host, \Monolog\Logger::DEBUG);
        $this->logger->pushHandler($logdnaHandler);
    }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new LogDNAService();
        }
        return self::$instance;
    }

    public function log($level, $key, $data) {
        return $this->logger->log($level, $key, $data);
    }

}