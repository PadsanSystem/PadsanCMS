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

include LOCALE.LOCALESET."search/members.php";

if ($_GET['stype'] == "members" || $_GET['stype'] == "all") {
	if (!$settings['hide_userprofiles'] || iMEMBER) {
		$rows = dbcount("(user_id)", DB_USERS, "user_status='0' AND user_name LIKE '%".$_GET['stext']."%'");
		if ($rows != 0) {
			$items_count .= THEME_BULLET."&nbsp;<a href='".FUSION_SELF."?stype=members&amp;stext=".$_GET['stext']."&amp;".$composevars."'>".$rows." ".($rows == 1 ? $locale['m401'] : $locale['m402'])." ".$locale['522']."</a><br />\n";
			$result = dbquery("
			SELECT user_id, user_name, user_status FROM ".DB_USERS."
			WHERE user_status='0' AND user_name LIKE '%".$_GET['stext']."%'
			ORDER BY user_name".($_GET['stype'] != "all" ? " LIMIT ".$_GET['rowstart'].",10" : "")
			);
			while ($data = dbarray($result)) {
				$search_result = profile_link($data['user_id'], $data['user_name'], $data['user_status'])."<br />\n";
				search_globalarray($search_result);
			}
		} else {
			$items_count .= THEME_BULLET."&nbsp;0 ".$locale['m402']." ".$locale['522']."<br />\n";
		}
		$navigation_result = search_navigation($rows);
	} else {
		$items_count .= THEME_BULLET."&nbsp;0 <span class='small'>(".$locale['m403'].")</span><br />\n";
	}
}
?>