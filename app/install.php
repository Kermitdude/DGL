<?php
/**
 *  Install script
 *  
 *  Creates initial user account and admin roles
 *  Applies initial settings
 */


// Create settings
DigitalGaming\Setting::add("installed", true);

// Create role
$superrole = new DigitalGaming\Role();
$superrole->setName('Super');

// Create superuser
$superuser = new DigitalGaming\User();
$superuser->setName('Aita');
$superuser->setEmail('aita@blacklightcommunity.org');
$superuser->setPassword($superuser->hashPassword('admin'));

// Create permission
$superperm = new DigitalGaming\Permission();
$superperm->setName('home.view');

// Associate permission and role
$superrole->addPermission($superperm);
$superuser->addRole($superrole);

$superuser->save();

unset($superuser);
unset($superrole);
unset($superperm);
?>