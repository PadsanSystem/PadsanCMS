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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

render_footer(true);

echo "</body>\n</html>\n";

if (iADMIN) {
	$result = dbquery("DELETE FROM ".$db_prefix."flood_control WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."captcha WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_datestamp < '".(time()-86400)."'");
}

mysql_close();

ob_end_flush();
?>