<?php
// Including global autoloader
require_once '/../vendor/autoload.php';

// Database
require_once '/../generated-conf/config.php';

// Create session
DigitalGaming\Session::update(); 

// Logs
$log = new Monolog\Logger('Audit');
$logDir = $_SERVER['DOCUMENT_ROOT'] . '../../shared/';
$log->pushHandler(new Monolog\Handler\StreamHandler($logDir . 'audit.log', Monolog\Logger::INFO));
Propel\Runtime\Propel::getServiceContainer()->setLogger('Audit', $log);

// First-time install
$installed = DigitalGaming\Setting::get("installed");
if (!$installed) require_once 'install.php';


?>