<?php
/*
|--------------------------------|
|		PadsanSystem CMS		 |
|--------------------------------|
|		 General Version		 |
|--------------------------------|
|Web   : www.PadsanCMS.com		 |
|Email : Support@PadsanCMS.com	 |
|Tel   : +98 - 26 325 45 700	 |
|Fax   : +98 - 26 325 45 701	 |
|--------------------------------|
*/
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

if (iMEMBER) redirect("index.php");

function __autoload($class) {
  require CLASSES.$class.".class.php";
  if (!class_exists($class)) { die("Class not found"); }
}

add_to_title($locale['global_200'].$locale['400']);
opentable($locale['400']);

$obj = new LostPassword();
if (isset($_GET['user_email']) && isset($_GET['account'])) {
	$obj->checkPasswordRequest($_GET['user_email'], $_GET['account']);
	$obj->displayOutput();
} elseif (isset($_POST['send_password'])) {
	$obj->sendPasswordRequest($_POST['email']);
	$obj->displayOutput();
} else {
	$obj->renderInputForm();
	$obj->displayOutput();
}

closetable();

require_once THEMES."templates/footer.php";
?>