<?php
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }
if (!iMEMBER || !isset($_POST['user_hash']) || $_POST['user_hash'] != $userdata['user_password']) fallback("index.php");

$error = ""; $set_avatar = "";

$username = trim(eregi_replace(" +", " ", $_POST['user_name']));
if ($username == "" || $_POST['user_email'] == "") {
	$error .= $locale['480']."<br>\n";
} else {
	if (preg_match("/^[-0-9A-Z_@\s._]+$/i", $username)) {
		if ($username != $userdata['user_name']) {
			$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_name='$username'");
			if (dbrows($result) != 0) $error = $locale['482']."<br>\n";
		}
	} else {
		$error .= $locale['481']."<br>\n";
	}
	
	if (preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['user_email'])) {
		if ($_POST['user_email'] != $userdata['user_email']) {
			$result = dbquery("SELECT user_email FROM ".$db_prefix."users WHERE user_email='".$_POST['user_email']."'");
			if (dbrows($result) != 0) $error = $locale['484']."<br>\n";
		}
	} else {
		$error .= $locale['483']."<br>\n";
	}
}

if ($_POST['user_newpassword'] != "") {
	if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
		$error .= $locale['485']."<br>";
	} else {
		if ($_POST['user_hash'] == $userdata['user_password']) {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['user_newpassword'])) {
				$error .= $locale['486']."<br>\n";
			}
		} else {			
			$error .= $locale['487']."<br>\n";
		}
	}
}

$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
	$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "")
	."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "")
	."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "");

$user_first_name = isset($_POST['user_first_name']) ? trim(stripinput(addslash($_POST['user_first_name']))) : "";
$user_last_name = isset($_POST['user_last_name']) ? trim(stripinput(addslash($_POST['user_last_name']))) : "";
include INCLUDES."location_list.php";
$sex = array(
    0    => 'مرد',
    1    => 'زن',
	);
if (in_array("".$user_sex."", $sex)) {
	$user_sex = isset($_POST['user_sex']) ? trim(stripinput(addslash($_POST['user_sex']))) : "";
}else{
	$user_sex = $userdata['user_sex'];
}
$user_aim = isset($_POST['user_aim']) ? trim(stripinput(addslash($_POST['user_aim']))) : "";
$user_icq = isset($_POST['user_icq']) ? trim(stripinput(addslash($_POST['user_icq']))) : "";
$user_msn = isset($_POST['user_msn']) ? trim(stripinput(addslash($_POST['user_msn']))) : "";
$user_yahoo = isset($_POST['user_yahoo']) ? trim(stripinput(addslash($_POST['user_yahoo']))) : "";

if (in_array("".$user_country."", $country)) {
	$user_country = isset($_POST['user_country']) ? trim(stripinput(addslash($_POST['user_country']))) : "";
}else{
	$user_country = $userdata['user_country'];
}
if (in_array("".$user_states."", $states)) {
	$user_states = isset($_POST['user_states']) ? trim(stripinput(addslash($_POST['user_states']))) : "";
}else{
	$user_states = $userdata['user_states'];
}
if (in_array("".$user_city."", $city)) {
	$user_city = isset($_POST['user_city']) ? trim(stripinput(addslash($_POST['user_city']))) : "";
}else{
	$user_city = $userdata['user_city'];
}
$user_web = isset($_POST['user_web']) ? trim(stripinput(addslash($_POST['user_web']))) : "";
$user_sig = isset($_POST['user_sig']) ? trim(stripinput($_POST['user_sig'])) : "";


if ($error == "") {
	$newavatar = $_FILES['user_avatar'];
		$avatarext = strrchr($newavatar['name'],".");
		$avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
		if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $avatarname) && preg_match("/(\.gif|\.GIF|\.jpg|\.JPG|\.png|\.PNG)$/", $avatarext) && $newavatar['size'] <= 5242880) {
			$avatarname = $avatarname.$userdata['user_id'].$avatarext;
			$set_avatar = "user_avatar='$avatarname', ";
			move_uploaded_file($newavatar['tmp_name'], IMAGES."avatars/".$avatarname);
			$size = (@getimagesize(IMAGES."avatars/".$avatarname));
			if ($size['0'] <= 130 || $size['1'] <= 130) {
				move_uploaded_file($newavatar['tmp_name'], IMAGES."avatars/".$avatarname);
			}elseif ($size['0'] > 130 || $size['1'] > 130){
			include INCLUDES."class.imageresizer.php";
			$thumb=new thumbnail("images/avatars/".$avatarname);            // generate image_file, set filename to resize
			$thumb->size_auto(130);                    // set the biggest width or height for thumbnail
			$thumb->jpeg_quality(90);                // [OPTIONAL] set quality for jpeg only (0 - 100) (worst - best), default = 75
			$thumb->show();                        // show your thumbnail
			$thumb->save("images/avatars/".$avatarname);                // save your thumbnail to file
			}
			chmod(IMAGES."avatars/".$avatarname,0644);
				if (!verify_image(IMAGES."avatars/".$avatarname)) {
					unlink(IMAGES."avatars/".$avatarname);
					$set_avatar = "";
				}
			} else {
				unlink(IMAGES."avatars/".$avatarname);
				$set_avatar = "";
			}
		}
	
	if (isset($_POST['del_avatar'])) {
		$set_avatar = "user_avatar='blank.gif', ";
		unlink(IMAGES."avatars/".$avatarname);
	}

	if ($user_newpassword != "") { $newpass = " user_password='".md5(md5($user_newpassword))."', "; } else { $newpass = " "; }
	$result = dbquery("UPDATE ".$db_prefix."users SET user_name='$username', user_first_name='$user_first_name', user_last_name='$user_last_name', user_sex='$user_sex', ".$newpass."user_email='".$_POST['user_email']."', user_hide_email='$user_hide_email', user_birthdate='$user_birthdate', user_aim='$user_aim', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_country='$user_country', user_states='$user_states', user_city='$user_city',user_web='$user_web', ".$set_avatar."user_sig='$user_sig' WHERE user_id='".$userdata['user_id']."'");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		redirect("edit_profile.php?update_profile=ok");
	}
?>