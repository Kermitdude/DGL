<?php
// Including global autoloader
require_once '/../vendor/autoload.php';

// Database
require_once '/../generated-conf/config.php';

// Create session
DigitalGaming\Session::update(); 

// Logs
$audit = new Monolog\Logger('Audit');
$auditDir = $_SERVER['DOCUMENT_ROOT'] . '../../shared/';
$audit->pushHandler(new Monolog\Handler\StreamHandler($auditDir . 'audit.log', Monolog\Logger::DEBUG));
Propel\Runtime\Propel::getServiceContainer()->setLogger('Audit', $audit);

// First-time install
$installed = DigitalGaming\Setting::get("installed");
if (!$installed) require_once 'install.php';


?>