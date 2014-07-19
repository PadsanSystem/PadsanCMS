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
require_once "../../maincore.php";
include INFUSIONS."invitations/locale/".$settings['locale'].".php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";

opentable($locale['inv200']);
	require_once INCLUDES."sendmail_include.php";
	$url = $settings['siteurl']."register.php";
	$message = "\r\n";
	$message .= $userdata['user_name'].$locale['inv401'].$settings['sitename'].$locale['inv204']."\r\n";
	$message .= $locale['inv402']."\r\n";
	$message .= $url."\r\n";
	//sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="")
	sendemail($settings['siteusername'],$_POST['txtEmail'],$user_name,$settings['siteemail'],$locale['inv400'].$settings['sitename'], $message,$type="plain",$cc="",$bcc="");
	echo "دعوتنامه با موفقيت فرستاده شد !!!";
closetable();
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>