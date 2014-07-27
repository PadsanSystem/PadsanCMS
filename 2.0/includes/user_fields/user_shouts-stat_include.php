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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	//Nothing here
} elseif ($profile_method == "display") {
	include_once INFUSIONS."shoutbox_panel/infusion_db.php";
	echo "<tr>\n";
	echo "<td class='tbl1'>".$locale['uf_shouts-stat']."</td>\n";
	echo "<td align='right' class='tbl1'>".number_format(dbcount("(shout_id)", DB_SHOUTBOX, "shout_name='".$user_data['user_id']."'"))."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>