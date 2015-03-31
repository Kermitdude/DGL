<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('dgl', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'classname' => 'Propel\\Runtime\\Connection\\ProfilerConnectionWrapper',
  'dsn' => 'mysql:host=localhost;port=3306;dbname=dgl',
  'user' => 'root',
  'password' => '',
));
$manager->setName('dgl');
$serviceContainer->setConnectionManager('dgl', $manager);
$serviceContainer->setDefaultDatasource('dgl');