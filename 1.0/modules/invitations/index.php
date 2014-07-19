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
function printInviteForm() {
	global $locale;
	echo "<center>";
	echo "<form name=\"invite_form\" action=\"successful.php\" method=\"post\">";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	if ($error == "") {
		echo "<tr>"
			."<td colspan=\"2\" align=\"center\"><p><b>".$locale['inv203']."</b><p></td>"
			."</tr>";
	} else {
		echo "<tr>"
			."<td colspan=\"2\" align=\"center\"><strong>".$error."</strong></td>"
			."</tr>";
	}
	echo "<tr>"
		."<td>".$locale['inv201']."</td>"
		."<td><input name=\"txtEmail\" style=\"width:200px\" type=\"text\" class=\"textbox\"></td>"
		."</tr>";
	echo "<tr>"
		."<td colspan=\"2\" align=\"center\"><br><input type=\"submit\" name=\"btnInvite\" value=\"".$locale['inv202']."\" class=\"button\"></td>"
		."</tr>";
	echo "</table></form>";
	echo "</center>";
}

printInviteForm();
closetable();
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>