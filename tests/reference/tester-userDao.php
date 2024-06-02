<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once __DIR__ . './../../model/persist/UserDao.php';
require_once __DIR__ . './../../lib/Debug.php';
require_once __DIR__ . './../../lib/Tester.php';


use SirAymane\ecommerce\model\persist\UserDao;
use SirAymane\ecommerce\model\User;
use SirAymane\ecommerce\lib\Debug;



Debug::iniset();


$dao = UserDao::getInstance();
$tester = new Tester();


$tester->truncateAllTables();

Debug::message("Select All-no data");
Debug::display($dao->selectAll());

//Init data test
$tester->initTestData();

Debug::message("Select All-data");
Debug::display($dao->selectAll());


Debug::message("Select by ID-Exists");
$user = new User(2);
$selected = $dao->select($user);
Debug::display([$selected]);
Debug::message(Debug::assert($selected->getId(), $user->getId()));

Debug::message("Select by ID-Not Exists");
$user = new User(22);
$selected = $dao->select($user);
Debug::message(Debug::assert($selected, null));

Debug::message("Update user-exists");
$user = new User(3,'namenew3','passnew3','admin','mail3@new.com',new DateTime('today'));
$result = $dao->update($user);
Debug::message(Debug::assert($result, 1));


Debug::message("Update user-Not-exists");
$user = new User(33,'namenew3','passnew3','admin','mail3@new.com',new DateTime('today'));
$result = $dao->update($user);
Debug::message(Debug::assert($result, 0));

Debug::message("Add user");
$user = new User(0,'namenew','passnew','admin','mail@new.com',new DateTime('today'));
$result = $dao->insert($user);
Debug::message(Debug::assert($result, 1));

Debug::message("Add user-duplicated username");
$user = new User(0,'namenew','passnew','admin','mail@new.com',new DateTime('today'));
$result = $dao->insert($user);
Debug::message(Debug::assert($result, 0));

Debug::message("Delete user-exists");
$user = new User(3);
$result = $dao->delete($user);
Debug::message(Debug::assert($result, 1));

Debug::message("Delete user-not-exists");
$user = new User(33);
$result = $dao->delete($user);
Debug::message(Debug::assert($result, 0));

Debug::message("Validate credentials-OK");
$user = new User();
$user->setUsername('user2');
$user->setPassword('pass2');
$result = $dao->validateCredentials($user->getUsername(), $user->getPassword());
Debug::message($result);
Debug::message(Debug::assert($result->getId(), 2));

Debug::message("Validate credentials-KO");
$user->setUsername('user2');
$user->setPassword('pass2222');
$result = $dao->validateCredentials($user->getUsername(), $user->getPassword());
Debug::message(Debug::assert($result, null));

