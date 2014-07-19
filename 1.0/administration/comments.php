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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/comments.php";

if (!checkrights("C") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($comment_id) && !isNum($comment_id)) fallback("index.php");

if (!isset($ctype) || !preg_match("/^[0-9A-Z]+$/i", $ctype)) fallback("../index.php");
if (!isset($cid) || !isNum($cid)) fallback("../index.php");

if (isset($_POST['save_comment'])) {
	$comment_message = $_POST['comment_message'];
	$result = dbquery("UPDATE ".$db_prefix."comments SET comment_message='$comment_message' WHERE comment_id='$comment_id'");
	redirect("comments.php?aid=".iAUTH."&ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	redirect("comments.php?aid=".iAUTH."&ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	if (!dbrows($result)) fallback("comments.php?ctype=$ctype&cid=$cid");
	$data = dbarray($result);
	tablebreak();
	opentable($locale['400']);
	echo "<form name='inputform' method='post' action='".$PHP_SELF.$aidlink."&amp;comment_id=$comment_id&amp;ctype=$ctype&amp;cid=$cid'>
<table align='center' cellpadding='0' cellspacing='0' width='400' >
<tr>
<td align='center' class='tbl'><textarea id='comment_message' name='comment_message' rows='5' class='textbox' style='width:400px'>".$data['comment_message']."</textarea>
</tr>";
if ($settings['tinymce_enabled'] == 1){
		echo '<script type="text/javascript" src="'.INCLUDES.'/editor/tiny_mce.js"></script>
		<script type="text/javascript">
		tinyMCE.init({
			plugins:"emotions",
			mode : "exact",
			elements : "comment_message",
			theme_advanced_toolbar_align : "center"
		});
		</script>';}
echo "<tr>
<td align='center' class='tbl'><br><input type='submit' name='save_comment' value='".$locale['401']."' class='button'></td>
</tr>
</table>";
tablebreak();
echo"
</form>\n";
	closetable();
	tablebreak();
}
opentable($locale['410']);
$i = 0;
$result = dbquery(
	"SELECT * FROM ".$db_prefix."comments LEFT JOIN ".$db_prefix."users
	ON ".$db_prefix."comments.comment_name=".$db_prefix."users.user_id
	WHERE comment_type='$ctype' AND comment_item_id='$cid' ORDER BY comment_datestamp ASC"
);
if (dbrows($result)) {
	echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	while ($data = dbarray($result)) {
		echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."' class='slink'>".$data['user_name']."</a>";
		} else {
			echo $data['comment_name'];
		}
		$comment_message = $data['comment_message'];
		echo "</span>
<span class='small'>".$locale['041'].showdate("longdate", $data['comment_datestamp'])."</span><br>
".$comment_message."<br>
<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;step=edit&amp;comment_id=".$data['comment_id']."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['411']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;step=delete&amp;comment_id=".$data['comment_id']."&amp;ctype=$ctype&amp;cid=$cid' onClick='return DeleteItem()'>".$locale['412']."</a> -
<b>".$locale['413'].$data['comment_ip']."</b></span>
</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
} else {
	echo "<center><br>".$locale['415']."<br><br></center>\n";
}
closetable();
echo "<script>
function DeleteItem() {
	return confirm(\"".$locale['414']."\");
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>