<?php
/*
|--------------------------------|
|		PadsanSystem CMS		 |
|--------------------------------|
|		 General Version		 |
|--------------------------------|
|Web   : www.PadsanCMS.com		 |
|Email : Support@PadsanCMS.com	 |
|Tel   : +98 - 26 325 45 700	 |
|Fax   : +98 - 26 325 45 701	 |
|--------------------------------|
*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// Display user field input
if ($profile_method == "input") {
	$user_skype = isset($user_data['user_skype']) ? $user_data['user_skype'] : "";
	if ($this->isError()) { $user_skype = isset($_POST['user_skype']) ? stripinput($_POST['user_skype']) : $user_skype; }
	
	echo "<tr>\n";
	echo "<td class='tbl".$this->getErrorClass("user_skype")."'><label for='user_skype'>".$locale['uf_skype'].$required."</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_skype")."'>";
	echo "<input type='text' id='user_skype' name='user_skype' value='".$user_skype."' maxlength='16' class='textbox' style='width:200px;' />";
	echo "</td>\n</tr>\n";

	if ($required) { $this->setRequiredJavaScript("user_skype", $locale['uf_skype_error']); }
	
// Display in profile
} elseif ($profile_method == "display") {
	if ($user_data['user_skype']) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['uf_skype']."</td>\n";
		echo "<td align='right' class='tbl1'><a href='skype:".$user_data['user_skype']."?add'>".$user_data['user_skype']."</a></td>\n";
		echo "</tr>\n";
	}
	
// Insert or update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_skype']) && ($_POST['user_skype'] != "" || $this->_isNotRequired("user_skype"))) {
		// Set update or insert user data
		$this->_setDBValue("user_skype", stripinput(trim($_POST['user_skype'])));
	} else {
		$this->_setError("user_skype", $locale['uf_skype_error'], true);	
	}
}
?>