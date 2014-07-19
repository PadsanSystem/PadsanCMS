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
	include LOCALE.LOCALESET."members-profile.php";
	include LOCALE.LOCALESET."user_fields.php";
echo "</div>";
if (isset($_POST['update_profile'])) require_once INCLUDES."update_profile_include.php";

opentable($locale['440']);
if (iMEMBER) {
	if ($userdata['user_birthdate']!="0000-00-00") {
		$user_birthdate = explode("-", $userdata['user_birthdate']);
		$user_month = number_format($user_birthdate['1']);
		$user_day = number_format($user_birthdate['2']);
		$user_year = $user_birthdate['0'];
	}else{
		$user_month = 0; $user_day = 0; $user_year = 0;
	}
	echo "<form name='inputform' method='post' action='".$PHP_SELF."' enctype='multipart/form-data'>\n";
	echo "<table align='right' cellpadding='0' cellspacing='10'>\n";
	if (isset($update_profile)) {
		if (!isset($error)) {
			echo "<tr>\n<td colspan='2' class='tbl'>".$locale['441']."<br><br>\n</td>\n</tr>\n";
		} else {
			echo "<tr>\n<td colspan='2' class='tbl'>".$locale['442']."<br><br>\n$error<br></td>\n</tr>\n";
			unset($error);
		}
	}
	echo "<tr>
<td class='tbl'>".$locale['u001']."</td>
<td class='tbl'><span style='color:#ff0000'>* </span><input type='text' name='user_name' value='".$userdata['user_name']."' maxlength='30' class='textbox' style='width:200px;' READONLY DISABLED></td>
</tr>
<tr>
<td class='tbl'>".$locale['u023']."</td>
<td class='tbl'>&nbsp;&nbsp;&nbsp;<input type='text' name='user_first_name' value='".$userdata['user_first_name']."' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u024']."</td>
<td class='tbl'>&nbsp;&nbsp;&nbsp;<input type='text' name='user_last_name' value='".$userdata['user_last_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u025']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><select name='user_sex' class='textbox' style='width:100px;'>\n";
$sex = array(
    0    => 'مرد',
    1    => 'زن',
	);
for ($i=0;$i<count($sex);$i++) echo "<option".($userdata['user_sex'] == $sex[$i] ? " selected" : "").">".$sex[$i]."</option>\n";
echo"</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u003']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='password' name='user_newpassword' maxlength='20' AUTOCOMPLETE='off' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u004']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='password' name='user_newpassword2' maxlength='20' AUTOCOMPLETE='off' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u005']."</td>
<td class='tbl'><span style='color:#ff0000'>* </span><input type='text' name='user_email' value='".$userdata['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>";

/*<tr>
<td class='tbl'>".$locale['u006']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='radio' name='user_hide_email' value='1'".($userdata['user_hide_email'] == "1" ? " checked" : "").">".$locale['u007']."
<input type='radio' name='user_hide_email' value='0'".($userdata['user_hide_email'] == "0" ? " checked" : "").">".$locale['u008']."</td>
</tr>
<tr>
<td class='tbl'>".$locale['u010']." <span class='small2'>( روز / ماه / سال )</span></td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><select name='user_day' class='textbox'>\n";
	for ($i=1;$i<=31;$i++) echo "<option".($user_day == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_month' class='textbox'>\n";
	for ($i=1;$i<=12;$i++) echo "<option".($user_month == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_year' class='textbox'>\n";
	for ($i=1320;$i<=1379;$i++) echo "<option".($user_year == $i ? " selected" : "").">$i</option>\n";
echo "</select>
</td>
</tr>
<tr>
<td class='tbl'>".$locale['u021']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='text' name='user_aim' value='".$userdata['user_aim']."' maxlength='16' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u011']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='text' name='user_icq' value='".$userdata['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u012']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='text' name='user_msn' value='".$userdata['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u013']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='text' name='user_yahoo' value='".$userdata['user_yahoo']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u026']."</td>
<td class='tbl'>&nbsp;&nbsp;&nbsp;</span><select name='user_country' class='textbox'>\n";
include INCLUDES."location_list.php";
	for ($i=1;$i<=count($country);$i++) echo "<option".($userdata['user_country'] == $country[$i] ? " selected" : "").">".$country[$i]."</option>\n";
echo "</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u027']."</td>
<td class='tbl'>&nbsp;&nbsp;&nbsp;</span><select name='user_states' class='textbox' style='width:100px;'>";
	for ($i=1;$i<=count($states);$i++) echo "<option".($userdata['user_states'] == $states[$i] ? " selected" : "").">".$states[$i]."</option>\n";
echo"</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u028']."</td>
<td class='tbl'>&nbsp;&nbsp;&nbsp;</span><select name='user_city' class='textbox' style='width:100px;'>";
	for ($i=1;$i<=count($city);$i++) echo "<option".($userdata['user_city'] == $city[$i] ? " selected" : "").">".$city[$i]."</option>\n";
echo "</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u014']."</td>
<td class='tbl'><span style='color:#ff0000'>&nbsp;&nbsp;&nbsp;</span><input type='text' name='user_web' value='".$userdata['user_web']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>\n";
	if ($userdata['user_avatar']) {
		echo "<tr>
<td class='tbl'>".$locale['u017']."</td>
<td class='tbl'>&nbsp;&nbsp;
<input type='file' name='user_avatar' class='textbox' style='width:200px;'><br>
<span class='small2'>".$locale['u018']."</span><br>
<span class='small2'>".sprintf($locale['u022'], parsebytesize(5242880))."</span>
</td>
</tr>\n";
	}

echo "<tr>
<td valign='top' class='tbl'>".$locale['u020']."</td>
<td class='tbl'>
<textarea id='edit_profile' name='user_sig' class='textbox' style='width:295px'>".$userdata['user_sig']."</textarea><br>";
if ($settings['tinymce_enabled'] == 1){
echo '<script type="text/javascript" src="includes/editor/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "exact",
	elements : "edit_profile",
	theme_advanced_toolbar_align : "center",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,|,fontselect,fontsizeselect"
});
</script>';}
if ($userdata['user_avatar']) {
		echo $locale['u017']."<br>\n<img src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='".$locale['u017']."'><br>
<input type='checkbox' name='del_avatar' value='y'> ".$locale['u019']."
<input type='hidden' name='user_avatar' value='".$userdata['user_avatar']."'><br><br>\n";
	}
	*/
	echo "<tr><td colspan='2'><center><input type='hidden' name='user_hash' value='".$userdata['user_password']."'>
<input type='submit' name='update_profile' value='".$locale['460']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
} else {
	echo "<center><br>\n".$locale['003']."<br>\n<br></center>\n";
	closetable();
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>