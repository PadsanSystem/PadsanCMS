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

if (isset($_POST['previewreply'])) {
	$sig_checked = isset($_POST['show_sig']) ? " checked" : "";
	if ($settings['thread_notify']) $notify_checked = isset($_POST['notify_me']) ? " checked" : "";
	opentable($locale['402']);
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	$message = trim(stripslash(censorwords($_POST['message'])));
	if ($_POST['subject'] == "") {
		$previewsubject = "RE: ".$tdata['thread_subject'];
	} else {
		$previewsubject = "RE: ".$subject;
	}
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata['user_sig']; }
	}
	$is_mod = iMOD && iUSER < "102" ? true : false;
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td width='145' class='tbl2'>".$locale['422']."</td>
<td class='tbl2'>$previewsubject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$userdata['user_name']."<br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($userdata['user_level']))."</span><br><br>\n";
	if ($userdata['user_avatar']) {
		echo "<img src='".IMAGES."avatars/".$userdata['user_avatar']."'><br><br>\n";
		$height = "200";
	} else {
		$height = "60";
	}
	echo "<span class='alt'>".$locale['423']."</span> ".$userdata['user_posts']."<br>\n";
	if ($userdata['user_location']) echo "<span class='alt'>".$locale['424']."</span> ".$userdata['user_location']."<br>\n";
	echo "<span class='alt'>".$locale['425']."</span> ".showdate("l j F Y", $userdata['user_joined'])."</td>
<td class='tbl1'><span class='small'>".$locale['426'].showdate("forumdate", time())."</span></td>
</tr>
<tr>
<td height='$height' valign='top' class='tbl1'>$previewmessage</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['postreply'])) {
	$flood = false; $error = 0;
	$sig = isset($_POST['show_sig']) ? "1" : "0";
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	if ($_POST['subject'] == "") {
		$subject = "RE: ".$tdata['thread_subject'];
	} else {
		$subject = "RE: ".$subject;
	}
	$message = trim(stripslash(censorwords($_POST['message'])));
	if (iMEMBER) {
		if ($subject != "" && $message != "") {
			$result = dbquery("SELECT MAX(post_datestamp) AS last_post FROM ".$db_prefix."posts WHERE post_author='".$userdata['user_id']."'");
			if (!iSUPERADMIN || dbrows($result) > 0) {
				$data = dbarray($result);
				if ((time() - $data['last_post']) < $settings['flood_interval']) {
					$flood = true;
					$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
					if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
						$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
					}
					fallback("viewforum.php?forum_id=$forum_id");
				}
			}
			if (!$flood) {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."'".$update_notify." WHERE thread_id='$thread_id'");
				$result = dbquery("INSERT INTO ".$db_prefix."posts (forum_id, thread_id, post_subject, post_message, post_showsig, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('$forum_id', '$thread_id', '$subject', '$message', '$sig', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '0', '0')");
				$newpost_id = mysql_insert_id();
				$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
				if ($settings['thread_notify'] && isset($_POST['notify_me'])) {
					if (!dbcount("(thread_id)", "thread_notify", "thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'")) {
						$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
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
								if (!$error) $result = dbquery("INSERT INTO ".$db_prefix."forum_attachments (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('$thread_id', '$newpost_id', '$attachname', '$attachext', '".$attach['size']."')");
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
			}
		} else {
			$error = 3;
		}
	} else {
		$error = 4;
	}
	if ($error > 2) { 
		redirect("postify.php?post=reply&error=$error&forum_id=$forum_id&thread_id=$thread_id");
	} else {
		redirect("postify.php?post=reply&error=$error&forum_id=$forum_id&thread_id=$thread_id&post_id=$newpost_id");
	}
} else {
	if (!isset($_POST['previewreply'])) {
		$subject = "";
		$message = "";
		$sig_checked = " checked";
	}
	if (isset($quote) && isNum($quote)) {
		$result = dbquery("SELECT * FROM ".$db_prefix."posts INNER JOIN ".$db_prefix."users ON ".$db_prefix."posts.post_author=".$db_prefix."users.user_id WHERE thread_id='$thread_id' and post_id='$quote'");
		if (dbrows($result) != "0") {
			$data = dbarray($result);
			$message = "<span style='background-color: #c0c0c0;'>".$data['user_name'].$locale['429']."\n".$data['post_message']."";
		}
	}
	opentable($locale['403'].": $caption");
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?action=reply&amp;forum_id=$forum_id&amp;thread_id=$thread_id' enctype='multipart/form-data'>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td valign='middle' width='145' class='tbl2'>".$locale['460']."</td>
<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='255' style='width:250px'>".$locale['465']."</td>
</tr>
<tr>
<td valign='middle' width='145' class='tbl2'>".$locale['461']."</td>
<td class='tbl1'><textarea id='messages' name='message' cols='80' rows='15' class='textbox'>$message</textarea></td>
</tr>";
if ($settings['tinymce_enabled'] == 1){
echo '<script type="text/javascript" src="../../includes/editor/scripts/wysiwyg-modules.js"></script>
	  <script type="text/javascript" src="../../includes/editor/scripts/wysiwyg-settings.js"></script>
<script type="text/javascript">WYSIWYG.attach("messages");</script>';}
echo"<tr>
<td valign='middle' width='145' class='tbl2'>".$locale['463']."</td>
<td class='tbl1'>
<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".$locale['483'];
	if ($userdata['user_sig']) echo "<br>\n<input type='checkbox' name='show_sig' value='1'$sig_checked>".$locale['481'];
	if ($settings['thread_notify']) {
		if (!isset($_POST['previewreply']) && dbcount("(thread_id)", "thread_notify", "thread_id=$thread_id AND notify_user='".$userdata['user_id']."'")) {
			$notify_checked = " checked";
		}
		echo "<br>\n<input type='checkbox' name='notify_me' value='1'$notify_checked>".$locale['485'];
	}
	echo "</td>
</tr>\n";
	if ($settings['attachments'] == "1") {
		echo "<tr>
<td valign='middle' width='145' class='tbl2'>".$locale['464']."</td>
<td class='tbl1'><input type='file' name='attach' enctype='multipart/form-data' class='textbox' style='width:200px;'><br>
<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span></td>
</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewreply' value='".$locale['402']."' class='button'>
<input type='submit' name='postreply' value='".$locale['404']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
?>