<?php
require_once('core/header.php');
$User = new User;
if($User -> Is_Login())
	$User -> Dashboard();
else
	echo $User -> LoginForm();
require_once('core/footer.php');
?>