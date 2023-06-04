<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/UserDao.php";
require_once "model/User.php";

use proven\store\model\persist\UserDao;
use proven\store\model\User; // Testing the class and its methods

$dao = new UserDao();

// Returns the array of all the entities.
debug\Debug::display($dao->selectAll());

// Returns an array with only the specific user
debug\Debug::display($dao->selectWhere("username", "user05"));
debug\Debug::display($dao->selectWhere("username", "user99"));
debug\Debug::display($dao->selectWhere("username", "user02"));


// In order to add, it cannot add a user with same lastname and firstname
// LastName and firstname combination must be different. 
// Altough individually there can be the same last or first.

// It should return the number 0 refering to the number of users added.

// debug\Debug::print_r($dao->insert(new User(0, "peter01", "ppass01", "peter", "frampton", "registered")));
// debug\Debug::print_r($dao->update(new User(7, "peter11", "ppass11", "peter1", "frampton1", "admin")));


// Returns insert user result, 0 if it was not inserted and 1 if it was added.
// Have in mind that we can have firstname and lastname as unique.

// PDOException
echo($dao->insert(new User(0, "peter08", "ppass01", "peter", "framtton", "registered")));
echo($dao->insert(new User(0, "peter08", "ppass01", "peter", "framtton", "registered")));

// Deletes user if it exists
echo($dao->delete(new User(0)));



