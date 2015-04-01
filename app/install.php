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
$installVars = [];

$installVars['superrole'] = new DigitalGaming\Role();
$installVars['superrole']->setName('Super');
$installVars['superrole']->setAnnotation('God mode');

// Create superuser
$installVars['superuser']  = new DigitalGaming\User();
$installVars['superuser']->setName('Aita');
$installVars['superuser']->setEmail('aita@blacklightcommunity.org');
$installVars['superuser']->setPassword($installVars['superuser']->hashPassword('admin'));

// Create permissions
$installVars['adminIndex'] = new DigitalGaming\Permission();
$installVars['adminIndex']->setName('Admin.Index');
$installVars['adminIndex']->setAnnotation('Admin dashboard');

$installVars['adminUsers'] = new DigitalGaming\Permission();
$installVars['adminUsers']->setName('Admin.Users');
$installVars['adminUsers']->setAnnotation('Admin users');

$installVars['adminRoles'] = new DigitalGaming\Permission();
$installVars['adminRoles']->setName('Admin.Roles');
$installVars['adminRoles']->setAnnotation('Admin roles');

$installVars['adminPermissions'] = new DigitalGaming\Permission();
$installVars['adminPermissions']->setName('Admin.Permissions');
$installVars['adminPermissions']->setAnnotation('Admin permissions');

// Associate permission and role
$installVars['superrole']->addPermission($installVars['adminIndex']);
$installVars['superrole']->addPermission($installVars['adminUsers']);
$installVars['superrole']->addPermission($installVars['adminRoles']);
$installVars['superrole']->addPermission($installVars['adminPermissions']);

$installVars['superuser']->addRole($installVars['superrole']);

$installVars['superuser']->save();

unset($installVars);
?>