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

openside($locale['global_032']);
$result = dbquery(
		"SELECT td.download_id, td.download_title, td.download_cat, td.download_datestamp,
				tc.download_cat_id, tc.download_cat_access 
			FROM ".DB_DOWNLOADS." td
			INNER JOIN ".DB_DOWNLOAD_CATS." tc ON td.download_cat=tc.download_cat_id
			".(iSUPERADMIN ? "" : " WHERE ".groupaccess('download_cat_access'))." 
			ORDER BY download_datestamp DESC LIMIT 0,5");
if (dbrows($result)) {
	while($data = dbarray($result)) {
		$download_title = trimlink($data['download_title'], 23);
		echo THEME_BULLET." <a href='".BASEDIR."downloads.php?download_id=".$data['download_id']."' title='".$data['download_title']."' class='side'>".$download_title."</a><br />\n";
	}
} else {
	echo "<div style='text-align:center'>".$locale['global_033']."</div>\n";
}
closeside();
?>