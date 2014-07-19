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
echo "<div style='position:absolute'>";
	include LOCALE.LOCALESET."contact.php";
echo "</div>";

if (isset($_POST['sendmessage'])) {
	$error = "";
	$mailname = substr(stripinput(trim($_POST['mailname'])),0,50);
	$email = substr(stripinput(trim($_POST['email'])),0,100);
	$subject = substr(str_replace(array("\r","\n","@"), "", descript(stripslash(trim($_POST['subject'])))),0,50);
	$message = descript(stripslash(trim($_POST['message'])));
	if ($mailname == "") {
		$error .= "· <span class='alt'>".$locale['420']."</span><br>\n";
	}
	if ($email == "" || !preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= "· <span class='alt'>".$locale['421']."</span><br>\n";
	}
	if ($subject == "") {
		$error .= "· <span class='alt'>".$locale['422']."</span><br>\n";
	}
	if ($message == "") {
		$error .= "· <span class='alt'>".$locale['423']."</span><br>\n";
	}
	if (!$error) {
		require_once INCLUDES."sendmail_include.php";
		sendemail($settings['siteusername'],$settings['siteemail'],$mailname,$email,$subject,$message);
		opentable($locale['400']);
		echo "<center><br>\n".$locale['440']."<br><br>\n".$locale['441']."</center><br>\n";
		closetable();
	} else {
		opentable($locale['400']);
		echo "<center><br>\n".$locale['442']."<br><br>\n$error<br>\n".$locale['443']."</center><br>\n";
		closetable();
	}
} else {
	opentable($locale['400']);
	echo $locale['401']."<br><br>
<form name='userform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='10' class='tbl'>
<tr>
<td width='100'>".$locale['402']."</td>
<td><input type='text' name='mailname' maxlength='50' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='100'>".$locale['403']."</td>
<td><input type='text' name='email' maxlength='100' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='100'>".$locale['404']."</td>
<td><input type='text' name='subject' maxlength='50' class='textbox' style='width: 200px;'></td>
</tr>
<tr><td valign='top' width='90'>".$locale['405']."</td>
<td><textarea id='contact' name='message' rows='10' class='textbox' style='width: 320px'></textarea></td>
</tr>";
if ($settings['tinymce_enabled'] == 1){
echo '<script type="text/javascript" src="'.INCLUDES.'/editor/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "exact",
	elements : "contact",
	theme_advanced_toolbar_align : "center",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,|,fontselect,fontsizeselect"
});
</script>';}
echo"<tr>
<td align='center' colspan='2'>
<br><input type='submit' name='sendmessage' value='".$locale['406']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>