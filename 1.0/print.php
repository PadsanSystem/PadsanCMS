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
include LOCALE.LOCALESET."print.php";
if (!isset($item_id) || !isNum($item_id)) fallback("index.php");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
</head>
<style type=\"text/css\">
body { font-family:Tahoma,Arial,Sans-Serif;font-size:14px;direction:rtl; }
hr { height:1px;color:#ccc; }
.small { font-family:Tahoma,Arial,Sans-Serif;font-size:12px; }
.small2 { font-family:Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }
</style>
</head>
<body>\n";
if ($type == "A") {
	$res = dbquery(
		"SELECT ta.*, user_id, user_name FROM ".$db_prefix."articles ta
		LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
		WHERE article_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$article = str_replace("<--PAGEBREAK-->", "", stripslashes($data['article_article']));
		if ($data['article_breaks'] == "y") $article = nl2br($article);
		echo "<b>".$data['article_subject']."</b><br>
<hr/>
$article\n";
	}
} elseif ($type == "N") {
	$res = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$news = stripslashes($data['news_news']);
		if ($data['news_breaks'] == "y") $news = nl2br($news);
		if ($data['news_extended']) {
			$news_extended = stripslashes($data['news_extended']);
			if ($data['news_breaks'] == "y") $news_extended = nl2br($news_extended);
		}
		echo "<b>".$data['news_subject']."</b>
<hr/>
$news\n";
		if (isset($news_extended)) echo "<hr/>\n<hr/>\n$news_extended\n";
	}
}
echo "</body>
</html>\n";
?>