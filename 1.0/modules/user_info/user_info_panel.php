<?php
include LOCALE.LOCALESET."submit.php";
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

if (iMEMBER) {
	opensidex($userdata['user_name']);
	if ($userdata['user_avatar']) {
		echo "<hr/><center><img src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='".$locale['u017']."'/></center><hr/><br/>";
	}
	$msg_count = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
	if (iADMIN){
		echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."members.php' class='side'/>".$locale['082']."</a><br/>";
	}
echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."modules/invitations/index.php' class='side'/>دعوت دوستان</a><br/>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."edit_profile.php' class='side'/>".$locale['080']."</a><br/>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."messages.php' class='side'/>".$locale['081']."</a><br/>\n";
	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."' class='side'/>".$locale['083']."</a><br/>\n";
	}
	echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."index.php?logout=yes' class='side'/>".$locale['084']."</a><br/><hr/><br/>\n";
	if ($msg_count) echo "<br/><br/><center><b><a href='".BASEDIR."messages.php' style='color:#ff0000;'>".sprintf($locale['085'], $msg_count).($msg_count == 1 ? $locale['086'] : $locale['087'])."</a></b></center>\n";
} else {
	opensidex($locale['060']);
	echo "<div align='center'>".(isset($loginerror) ? $loginerror : "")."
<form name='loginform' method='post' action='".$PHP_SELF."'>
".$locale['061']."<br/>
<input type='text' name='user_name' class='textbox' style='width:100px'/><br/>
".$locale['062']."<br/>
<input type='password' name='user_pass' maxlength='20' autocomplete='off' class='textbox' style='width:100px'/><br/>
<input type='checkbox' name='remember_me' value='y' title='".$locale['063']."' style='vertical-align:middle;'/>
<input type='submit' name='login' value='".$locale['064']."' class='button'/><br/><hr/>".$locale['065']."
</form><br/>\n";
	
	echo $locale['066']."
</div>\n";
}
closesidex();
?>