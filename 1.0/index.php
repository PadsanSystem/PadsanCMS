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
if (isset($readmore) && !isNum($readmore)) fallback(FUSION_SELF);

// Predefined variables, do not edit these values
if ($settings['news_style'] == "1") { $i = 0; $rc = 0; $ncount = 1; $ncolumn = 1; $news_[0] = ""; $news_[1] = ""; $news_[2] = ""; } else { $i = 1; }

// This number should be an odd number to keep layout tidy
$items_per_page = 11;

if (!isset($readmore)) {
	$rows = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$result = dbquery(
			"SELECT tn.*, tc.*, user_id, user_name FROM ".$db_prefix."news tn
			LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
			LEFT JOIN ".$db_prefix."news_cats tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT $rowstart,$items_per_page"
		);		
		$numrows = dbrows($result);
		while ($data = dbarray($result)) {
			$news_cat_image = "";
			$news_subject = "<a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			if ($data['news_cat_image']) {
				$news_cat_image = "<a href='news_cats.php?cat_id=".$data['news_cat_id']."'><img src='".IMAGES_NC.$data['news_cat_image']."' alt='".$data['news_cat_name']."' align='right' style='border:0px;margin-top:3px;margin-right:5px'></a>";
			} else {
				$news_cat_image = "";
			}
			$news_news = stripslashes($data['news_news']);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'], 
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
				render_news($news_subject, $news_news, $news_info);
				if ($i != $numrows) { tablebreak(); } $i++;
		}
		if ($rows > $items_per_page) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$items_per_page,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['046']);
		echo "<center><br>\n".$locale['047']."<br><br>\n</center>\n";
		closetable();
	}
} else {
	echo "<div style='position:absolute'>";
	include INCLUDES."comments_include.php";
	include INCLUDES."ratings_include.php";
	echo "</div>";
	
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$readmore'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
		if (checkgroup($data['news_visibility'])) {
			$news_cat_image = "";
			if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
				 $result2 = dbquery("UPDATE ".$db_prefix."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
				 $data['news_reads']++;
			}
			$news_subject = $data['news_subject'];
			if ($data['news_cat'] != 0) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='".$data['news_cat']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".IMAGES_NC.$data2['news_cat_image']."' alt='".$data2['news_cat_name']."' align='right' style='border:0px;margin-top:3px;margin-right:5px'></a>";
				}
			}
			$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'],
				"news_ext" => "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			render_news($news_subject, $news_news, $news_info);
			if ($data['news_allow_comments']) showcomments("N","news","news_id",$readmore,FUSION_SELF."?readmore=$readmore");
			if ($data['news_allow_ratings']) showratings("N",$readmore,FUSION_SELF."?readmore=$readmore");
		} else {
			redirect(FUSION_SELF);
		}
	} else {
		redirect(FUSION_SELF);
	}
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>