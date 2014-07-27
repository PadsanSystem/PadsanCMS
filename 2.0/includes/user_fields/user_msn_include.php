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
	$user_msn = isset($user_data['user_msn']) ? $user_data['user_msn'] : "";
	if ($this->isError()) { $user_msn = isset($_POST['user_msn']) ? stripinput($_POST['user_msn']) : $user_msn; }

	echo "<tr>\n";
	echo "<td class='tbl".$this->getErrorClass("user_msn")."'><label for='user_msn'>".$locale['uf_msn'].$required."</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_msn")."'>";
	echo "<input type='text' id='user_msn' name='user_msn' value='".$user_msn."' maxlength='50' class='textbox' style='width:200px;' />";
	echo "</td>\n</tr>\n";

	if ($required) { $this->setRequiredJavaScript("user_msn", $locale['uf_msn_error']); }

// Display in profile
} elseif ($profile_method == "display") {
	if ($user_data['user_msn']) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['uf_msn']."</td>\n";
		echo "<td align='right' class='tbl1'>".hide_email($user_data['user_msn'])."</td>\n";
		echo "</tr>\n";
	}

// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_msn']) && ($_POST['user_msn'] != "" || $this->_isNotRequired("user_msn"))) {
		// Set update or insert user data
		$this->_setDBValue("user_msn", stripinput(trim($_POST['user_msn'])));
	} else {
		$this->_setError("user_msn", $locale['uf_msn_error'], true);	
	}
}
?>