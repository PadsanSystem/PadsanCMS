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
	require_once INCLUDES."bbcode_include.php";

	$user_sig = isset($user_data['user_sig']) ? $user_data['user_sig'] : "";
	if ($this->isError()) { $user_sig = isset($_POST['user_sig']) ? stripinput($_POST['user_sig']) : $user_sig; }

	echo "<tr>\n";
	echo "<td valign='top' class='tbl".$this->getErrorClass("user_sig")."'><label for='user_sig'>".$locale['uf_sig'].$required."</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_sig")."'>";
	echo "<textarea id='user_sig' name='user_sig' cols='60' rows='5' class='textbox' style='width:295px'>".$user_sig."</textarea><br />\n";
	echo display_bbcodes("300px", "user_sig", "inputform", "smiley|b|i|u||center|small|url|mail|img|color");
	echo "</td>\n</tr>\n";

	if ($required) { $this->setRequiredJavaScript("user_sig", $locale['uf_sig_error']); }

// Display in profile
} elseif ($profile_method == "display") {

// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_sig']) && ($_POST['user_sig'] != "" || $this->_isNotRequired("user_sig"))) {
		// Set update or insert user data
		$this->_setDBValue("user_sig", stripinput(trim($_POST['user_sig'])));
	} else {
		$this->_setError("user_sig", $locale['uf_sig_error'], true);	
	}
}
?>