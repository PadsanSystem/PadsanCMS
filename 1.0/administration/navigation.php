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
if (!defined("IN_FUSION")) { header("location: ../index.php"); exit; }
echo "<div style='position:absolute'>";
include LOCALE.LOCALESET."admin/main.php";
echo "</div>";
echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";

include INFUSIONS."user_info/user_info_panel.php";

openside($locale['001']);
// Find out which panels and pages the admin can access
$usr_rghts = " (admin_rights='".str_replace(".", "' OR admin_rights='", $userdata['user_rights'])."')";
$page1 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='1'");
$page2 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='2'");
$page3 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='3'");
$page4 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='4'");
if (iMEMBER) {
	if ($page1) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=1' class='side'>".$locale['ac01']."</a><br>\n";
	if ($page2) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=2' class='side'>".$locale['ac02']."</a><br>\n";
	if ($page3) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=3' class='side'>".$locale['ac03']."</a><br>\n";
	if ($page4) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=4' class='side'>".$locale['ac04']."</a><br>\n";
	echo "<hr class='side-hr'>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."index.php' class='side'>".$locale['151']."</a>\n";
}

closeside();

echo "</td>\n<td valign='top' class='main-bg'>\n";
?>