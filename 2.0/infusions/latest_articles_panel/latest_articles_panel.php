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

openside($locale['global_030']);
$result = dbquery(
	"SELECT ta.article_id, ta.article_subject, tac.article_cat_id, tac.article_cat_access FROM ".DB_ARTICLES." ta
	INNER JOIN ".DB_ARTICLE_CATS." tac ON ta.article_cat=tac.article_cat_id
	".(iSUPERADMIN ? "" : "WHERE ".groupaccess('article_cat_access'))." AND article_draft='0' ORDER BY article_datestamp DESC LIMIT 0,5"
);
if (dbrows($result)) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['article_subject'], 23);
		echo THEME_BULLET." <a href='".BASEDIR."articles.php?article_id=".$data['article_id']."' title='".$data['article_subject']."' class='side'>$itemsubject</a><br />\n";
	}
} else {
	echo "<div style='text-align:center'>".$locale['global_031']."</div>\n";
}
closeside();
?>