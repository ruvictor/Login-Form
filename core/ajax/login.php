<?php
// avoid direct access to this file
define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!AJAX_REQUEST) {die('Why did you come here? (*-*)');}
// require the User Class
require_once('../classes/user.Class.php');
// create new user bject
$User = new User;
// check the data
echo $User->CheckData($_POST['email'], $_POST['pass']);
?>
