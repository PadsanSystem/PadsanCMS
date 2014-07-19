<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fa" lang="fa">
<?php
/*
|--------------------------------|
|		Padsan System CMS		 |
|--------------------------------|
|		General Version			 |
|--------------------------------|
|Web   : www.PadsanCMS.com		 |
|Email : Support@PadsanCMS.com	 |
|Tel   : +98 - 261 2533135		 |
|Fax   : +98 - 261 2533136		 |
|--------------------------------|
*/
require_once THEME."theme.php";
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

if ($settings['maintenance'] == "1" && !iADMIN) fallback(BASEDIR."maintenance.php");
if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");
echo "<head><title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".THEME.$theme."' type='text/css'>
<script type='text/javascript' src='".INCLUDES."styleswitcher.js'></script>
<script type='text/javascript' src='".INCLUDES."reflection.js'></script>
</head><body>\n";

// Theme Cashing
header ("cache-control: must-revalidate");

//Render Header
render_header("<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' title='".$settings['sitename']."'");
?>