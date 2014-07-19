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
require_once "maincore.php";
include LOCALE.LOCALESET."faq.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
if (!isset($cat_id)) {
	opentable($locale['400']);
	$result = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$counter = 0; $columns = 1;
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl'>\n<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$num = dbcount("(faq_id)", "faqs", "faq_cat_id='".$data['faq_cat_id']."'");
			echo "<td align='right' valign='top'><a href='".FUSION_SELF."?cat_id=".$data['faq_cat_id']."'>".$data['faq_cat_name']."</a> <span class='small2'>($num)</span>\n";
			if ($data['faq_cat_description'] != "") echo "<br>\n<span class='small'>".$data['faq_cat_description']."</span>";
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".$locale['410']."<br><br>\n</center>\n";
	}
	closetable();
} else {
	if (!isNum($cat_id)) fallback(FUSION_SELF);
	if ($data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$cat_id'"))) {
		opentable($locale['401']." : ".$locale['1']." ".$data['faq_cat_name']);
		$rows = dbcount("(*)", "faqs", "faq_cat_id='$cat_id'");
		if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
		if ($rows != 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='$cat_id' ORDER BY faq_id LIMIT $rowstart,15");
			$numrows = dbrows($result);
			$i = 1;
			while ($data = dbarray($result)) {
				echo "<b>".$data['faq_question']."</b><p>\n".nl2br(stripslashes($data['faq_answer']));
				echo ($i != $numrows ? "<br><br>\n" : "\n");
				$i++;
			}
			closetable();
			if ($rows != 0) echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,15,$rows,3,FUSION_SELF."?cat_id=$cat_id&amp;")."\n</div>\n";
		} else {
			echo $locale['411']."\n";
			closetable();
		}
	} else {
		redirect(FUSION_SELF);
	}
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>