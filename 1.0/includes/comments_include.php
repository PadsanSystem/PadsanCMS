<?php
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

include LOCALE.LOCALESET."comments.php";

function showcomments($ctype,$cdb,$ccol,$cid,$clink) {

	global $settings,$locale,$userdata,$aidlink;
	
	if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {
		$flood = false;
		if (dbrows(dbquery("SELECT $ccol FROM ".DB_PREFIX."$cdb WHERE $ccol='$cid'"))==0) {
			fallback(BASEDIR."index.php");
		}
		if (iMEMBER) {
			$comment_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$comment_name = trim(stripinput($_POST['comment_name']));
			$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
			if (isNum($comment_name)) $comment_name="";
		}
		$comment_message = trim(censorwords($_POST['comment_message']));
		if ($comment_name != "" && $comment_message != "") {
			$result = dbquery("SELECT MAX(comment_datestamp) AS last_comment FROM ".DB_PREFIX."comments WHERE comment_ip='".USER_IP."'");
			if (!iSUPERADMIN || dbrows($result) > 0) {
				$data = dbarray($result);
				if ((time() - $data['last_comment']) < $settings['flood_interval']) {
					$flood = true;
					$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
					if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
						if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
					}
				}
			}
			if (!$flood) $result = dbquery("INSERT INTO ".DB_PREFIX."comments (comment_item_id, comment_type, comment_name, comment_message, comment_datestamp, comment_ip) VALUES ('$cid', '$ctype', '$comment_name', '$comment_message', '".time()."', '".USER_IP."')");
		}
		redirect($clink);
	}

	tablebreak();
	opentable($locale['c100']);
	$result = dbquery(
		"SELECT tcm.*,user_name FROM ".DB_PREFIX."comments tcm
		LEFT JOIN ".DB_PREFIX."users tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='$cid' AND comment_type='$ctype'
		ORDER BY comment_datestamp ASC"
	);
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>\n";
			if ($data['user_name']) {
				echo "<a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."'>".$data['user_name']."</a>";
			} else {
				echo $data['comment_name'];
			}
				$comment_message = $data['comment_message'];
			echo "</span>\n<span class='small'>".$locale['041'].showdate("longdate", $data['comment_datestamp'])."</span><br>\n";
			echo $comment_message."</td>\n</tr>\n";
			$i++;
		}
		if (checkrights("C")) echo "<tr>\n<td align='right' class='".($i% 2==0?"tbl1":"tbl2")."'><a href='".ADMIN."comments.php".$aidlink."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['c106']."</a></td>\n</tr>\n";
		echo "</table>\n";
	} else {
		echo $locale['c101']."\n";
	}
	closetable();
	tablebreak();
	opentable($locale['c102']);
	if (iMEMBER || $settings['guestposts'] == "1") {
		echo "<form name='inputform' method='post' action='$clink'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
		if (iGUEST) {
			echo "<tr>
<td>".$locale['c103']."</td>
</tr>
<tr>
<td><input type='text' name='comment_name' maxlength='30' class='textbox' style='width:100%;'></td>
</tr>\n";
		}
if ($settings['tinymce_enabled'] == 1){
echo '<script type="text/javascript" src="'.INCLUDES.'/editor/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "exact",
	elements : "comment_message",
	theme_advanced_toolbar_align : "center",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,|,fontselect,fontsizeselect"
});
</script>';}
		echo "<tr>
<td align='center'><textarea id='comment_message' name='comment_message' rows='6' class='textbox' style='width:400px'></textarea><br>
</tr>
<tr>
<td align='center'><br><input type='submit' name='post_comment' value='".$locale['c102']."' class='button'></td>
</tr>
</table>
</form>\n";
	} else {
		echo $locale['c105']."\n";
	}
	closetable();
}
?>