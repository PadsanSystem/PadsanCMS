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

include LOCALE.LOCALESET."search/faqs.php";

if ($_GET['stype'] == "faqs" || $_GET['stype'] == "all") {
	$sortby = "faq_id";
	$ssubject = search_querylike("faq_question");
	$smessage = search_querylike("faq_answer");
	if ($_GET['fields'] == 0) {
		$fieldsvar = search_fieldsvar($ssubject);
	} else if ($_GET['fields'] == 1) {
		$fieldsvar = search_fieldsvar($smessage);
	} else if ($_GET['fields'] == 2) {
		$fieldsvar = search_fieldsvar($ssubject, $smessage);
	} else {
		$fieldsvar = "";
	}
	if ($fieldsvar) {
		$result = dbquery(
			"SELECT fq.*, fc.* FROM ".DB_FAQS." fq
			LEFT JOIN ".DB_FAQ_CATS." fc ON fq.faq_cat_id=fc.faq_cat_id
			WHERE ".$fieldsvar
		);
		$rows = dbrows($result);
	} else {
		$rows = 0;
	}
	if ($rows != 0) {
		$items_count .= THEME_BULLET."&nbsp;<a href='".FUSION_SELF."?stype=faqs&amp;stext=".$_GET['stext']."&amp;".$composevars."'>".$rows." ".($rows == 1 ? $locale['fq401'] : $locale['fq402'])." ".$locale['522']."</a><br />\n";
		while ($data = dbarray($result)) {
			$search_result = "";
			$text_all = $data['faq_answer'];
			$text_all = search_striphtmlbbcodes($text_all);
			$text_frag = search_textfrag($text_all);
			// $text_frag = highlight_words($swords, $text_frag);
			$subj_c = search_stringscount($data['faq_question']);
			$text_c = search_stringscount($data['faq_answer']);

			$search_result .= "<a href='faq.php?cat_id=".$data['faq_cat_id']."'>".$data['faq_question']."</a>"."<br /><br />\n";

			// $search_result .= "<a href='faq.php?cat_id=".$data['faq_cat_id']."'>".highlight_words($swords, $data['faq_question'])."</a>"."<br /><br />\n";
			$search_result .= "<div class='quote' style='width:auto;height:auto;overflow:auto'>".$text_frag."</div><br />";
			$search_result .= "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['520'] : $locale['521'])." ".$locale['fq403']." ".$locale['fq404'].", ";
			$search_result .= $text_c." ".($text_c == 1 ? $locale['520'] : $locale['521'])." ".$locale['fq403']." ".$locale['fq405']."</span><br /><br />\n";
			search_globalarray($search_result);
		}
	} else {
		$items_count .= THEME_BULLET."&nbsp;0 ".$locale['fq402']." ".$locale['522']."<br />\n";
	}
	$navigation_result = search_navigation($rows);
}
?>