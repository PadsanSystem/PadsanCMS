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
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/post.php";

if (isset($_POST['previewchanges'])) {
	$del_check = isset($_POST['delete']) ? " checked" : "";
	$del_attach_check = isset($_POST['delete_attach']) ? " checked" : "";
	opentable($locale['405']);
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	$message = trim(stripslash(censorwords($_POST['message'])));
	if ($subject == "") $subject = $pdata['post_subject'];
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
	}
	$udata = dbarray(dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$pdata['post_author']."'"));
	$is_mod = in_array($udata['user_id'], $forum_mods) && $udata['user_level'] < "102" ? true : false;
	echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td width='145' class='tbl2'>".$locale['422']."</td>
<td class='tbl2'>$subject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$udata['user_name']."<br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($udata['user_level']))."</span><br><br>\n";
	if ($udata['user_avatar']) {
		echo "<img src='".IMAGES."avatars/".$udata['user_avatar']."'><br><br>\n";
		$height = "200";
	} else {
		$height = "60";
	}
	echo "<span class='alt'>".$locale['423']."</span> ".$udata['user_posts']."<br>\n";
	if ($udata['user_location']) echo "<span class='alt'>".$locale['424']."</span> ".$udata['user_location']."<br>\n";
	echo "<span class='alt'>".$locale['425']."</span> ".showdate("l j F Y", $udata['user_joined'])."</td>
<td class='tbl1'><span class='small'>".$locale['426'].showdate("forumdate", $pdata['post_datestamp'])."</span></td>
</tr>
<tr>
<td height='50' valign='top' class='tbl1'>$previewmessage<br/>
<br/>
<span class='small'>".$locale['427'].$userdata['user_name'].$locale['428'].showdate("forumdate", time())."</span>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['savechanges'])) {
	if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_id='$post_id' AND thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		$result = dbquery ("UPDATE ".$db_prefix."users SET user_posts = user_posts-1 WHERE user_id = '".$userdata['user_id']."'");

		if (dbrows($result) != 0) {
			$attach = dbarray($result);
			unlink(FORUM."attachments/".$attach['attach_name']);
			$result2 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		}
		$posts = dbcount("(post_id)", "posts", "thread_id='$thread_id'");
		if ($posts == 0) {
			$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id'");
		}
		// update forum_lastpost and forum_lastuser if post_datestamp matches
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastuser='".$pdata['post_author']."' AND forum_lastpost='".$pdata['post_datestamp']."'");
		if (dbrows($result)) {
			$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
			if (dbrows($result)) { 
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			} else {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
			}
		}
		// update thread_lastpost and thread_lastuser if thread post > 0 and post_datestamp matches
		if ($posts > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND thread_lastpost='".$pdata['post_datestamp']."' AND thread_lastuser='".$pdata['post_author']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT thread_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$pdata2['post_datestamp']."', thread_lastuser='".$pdata2['post_author']."' WHERE thread_id='$thread_id'");
			}
		}
		opentable($locale['407']);
		echo "<center><br>\n".$locale['445']."<br><br>\n";
		if ($posts > 0) echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id'>".$locale['447']."</a> |\n";
		echo "<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$error = 0;
		$subject = trim(stripinput(censorwords($_POST['subject'])));
		$message = trim(stripslash(censorwords($_POST['message'])));
		if (iMEMBER) {
			if ($subject != "" && $message != "") {
				$result = dbquery("UPDATE ".$db_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='".$userdata['user_id']."', post_edittime='".time()."' WHERE post_id='$post_id'");
				$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_id ASC LIMIT 1"));
				if ($data['post_id'] == $post_id) {
					$result = dbquery("UPDATE ".$db_prefix."threads SET thread_subject='$subject' WHERE thread_id='$thread_id'");
				}
				if (isset($_POST['delete_attach'])) {
					$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
					if (dbrows($result) != 0) {
						$adata = dbarray($result);
						unlink(FORUM."attachments/".$adata['attach_name']);
						$result = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
					}
				}
				if ($settings['attachments']) {
					$attach = $_FILES['attach'];
					if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name'])) {
						$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
						$attachext = strtolower(strrchr($attach['name'],"."));
						if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $attachname) && $attach['size'] <= $settings['attachmax']) {
							$attachtypes = explode(",", $settings['attachtypes']);
							if (in_array($attachext, $attachtypes)) {
								$attachname = attach_exists(strtolower($attach['name']));
								move_uploaded_file($attach['tmp_name'], FORUM."attachments/".$attachname);
								chmod(FORUM."attachments/".$attachname,0644);
								if (in_array($attachext, $imagetypes) && (!@getimagesize(FORUM."attachments/".$attachname) || !@verify_image(FORUM."attachments/".$attachname))) {
									unlink(FORUM."attachments/".$attachname);
									$error = 1;
								}
								if (!$error) $result = dbquery("INSERT INTO ".$db_prefix."forum_attachments (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('$thread_id', '$post_id', '$attachname', '$attachext', '".$attach['size']."')");
							} else {
								@unlink($attach['tmp_name']);
								$error = 1;
							}
						} else {
							@unlink($attach['tmp_name']);
							$error = 2;
						}
					}
				}
			} else {
				$error = 3;
			}
		} else {
			$error = 4;
		}
		redirect("postify.php?post=edit&error=$error&forum_id=$forum_id&thread_id=$thread_id&post_id=$post_id");
	}
} else {
	if (!isset($_POST['previewchanges'])) {
		$subject = $pdata['post_subject'];
		$message = $pdata['post_message'];
		$del_check = "";
	}
	opentable($locale['408']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?action=edit&amp;forum_id=$forum_id&amp;thread_id=$thread_id&amp;post_id=$post_id' enctype='multipart/form-data'>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td width='145' class='tbl2'>".$locale['460']."</td>
<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='255' style='width:250px'></td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>
<td class='tbl1'><textarea id='postedit' name='message' cols='80' rows='15' class='textbox'>$message</textarea></td>
</tr>";
if ($settings['tinymce_enabled'] == 1){
		echo '<script type="text/javascript" src="'.INCLUDES.'/editor/tiny_mce.js"></script>
		<script type="text/javascript">
		tinyMCE.init({
		plugins:"emotions",
		mode : "exact",
		elements : "postedit",
		theme_advanced_toolbar_align : "center"
		});
		</script>';
	}
echo"<tr>
<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>
<td class='tbl1'>
<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".$locale['483']."<br>
<input type='checkbox' name='delete' value='1'$del_check>".$locale['482']."
</td>
</tr>\n";
	if ($settings['attachments'] == "1") {
		echo "<tr>\n<td valign='top' width='145' class='tbl2'>".$locale['464']."</td>\n<td class='tbl1'>\n";
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result)) {
			$adata = dbarray($result);
			echo "<input type='checkbox' name='delete_attach' value='1'$del_attach_check>".$locale['484']."\n";
			echo "<a href='".FORUM."attachments/".$adata['attach_name']."'>".$adata['attach_name']."</a>\n";
		} else {
			echo "<input type='file' name='attach' enctype='multipart/form-data' class='textbox' style='width:200px;'><br>\n";
			echo "<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span>";
	
		}
		echo "</td>\n</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewchanges' value='".$locale['405']."' class='button'>
<input type='submit' name='savechanges' value='".$locale['409']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>