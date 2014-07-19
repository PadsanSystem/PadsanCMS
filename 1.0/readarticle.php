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
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
require_once INCLUDES."comments_include.php";
require_once INCLUDES."ratings_include.php";
if (!isset($article_id) || !isNum($article_id)) fallback("index.php");
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

$result = dbquery(
	"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".$db_prefix."articles ta
	INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
	LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
	WHERE article_id='$article_id'"
);
$res = 0;
if (dbrows($result) != 0) {
	$data = dbarray($result);
	if (checkgroup($data['article_cat_access'])) {
		$res = 1;
		if ($rowstart == 0) $result = dbquery("UPDATE ".$db_prefix."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
		$article = stripslashes($data['article_article']);
		$article = explode("<--PAGEBREAK-->", $article);
		$pagecount = count($article);
		$article_subject = stripslashes($data['article_subject']);
		$article_info = array(
			"article_id" => $data['article_id'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"article_files" => $data['article_files'],
			"article_date" => $data['article_datestamp'],
			"article_comments" => dbcount("(comment_id)", "comments", "comment_type='A' AND comment_item_id='".$data['article_id']."'"),
			"article_reads" => $data['article_reads'],
			"article_allow_comments" => $data['article_allow_comments']
		);
		render_article($article_subject, $article[$rowstart], $article_info);
		if (count($article) > 1) {
			$rows = $pagecount;
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,1,$rows,3,FUSION_SELF."?article_id=$article_id&amp;")."\n</div>\n";
		}
		if ($data['article_allow_comments']) showcomments("A","articles","article_id",$article_id,FUSION_SELF."?article_id=$article_id");
		if ($data['article_allow_ratings']) showratings("A",$article_id,FUSION_SELF."?article_id=$article_id");
	}
}
if ($res == 0) redirect("articles.php");
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>