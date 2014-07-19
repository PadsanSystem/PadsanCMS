<?php
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include INFUSIONS."sitemap/locale/".$settings['locale'].".php";

opentable($locale['SM100']);
//news
$result = dbquery("SELECT * FROM ".DB_PREFIX."news ORDER BY news_datestamp DESC");
$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM120']."</b>";
	echo "<p style='margin-left:20px'>";
	while ($data = dbarray($result)) {
		echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."index.php?readmore=".$data['news_id']."'>".$data['news_subject']."</a><br  />";
	}
	echo "<hr/>";
}

//articles
{
	$result = dbquery("
	SELECT * FROM ".DB_PREFIX."articles
	INNER JOIN ".DB_PREFIX."article_cats ON article_cat_id = article_cat
	ORDER BY article_name ASC");
}
$rows = dbrows($result);
if ($rows!=0) {
    echo "<b>".$locale['SM122']."</b>";
	echo "<p style='margin-left:20px'>";
	while ($data = dbarray($result)) {
		echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a><br  />";
	}
	echo "<hr/>";
}
//faqs
if (!defined("LANGUAGE")) {
   $result = dbquery("SELECT * FROM ".DB_PREFIX."faqs");
} else {
   $result = dbquery("
   SELECT * FROM ".DB_PREFIX."faqs ff
   INNER JOIN ".DB_PREFIX."faq_cats fc ON fc.faq_cat_id = ff.faq_cat_id
   ");
}
$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM125']."</b>";
	echo "<p style='margin-left:20px'>";
	while ($data = dbarray($result)) {
		echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."faq.php?cat_id=".$data['faq_cat_id']."'>".$data['faq_question']."</a><br  />";
	}
	echo "</p>";

}
closetable();
require_once BASEDIR."side_right.php";
include BASEDIR."footer.php";
?>