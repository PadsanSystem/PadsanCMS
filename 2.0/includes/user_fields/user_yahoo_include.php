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
	$user_yahoo = isset($user_data['user_yahoo']) ? $user_data['user_yahoo'] : "";
	if ($this->isError()) { $user_yahoo = isset($_POST['user_yahoo']) ? stripinput($_POST['user_yahoo']) : $user_yahoo; }

	echo "<tr>\n";
	echo "<td class='tbl".$this->getErrorClass("user_yahoo")."'><label for='user_yahoo'>".$locale['uf_yahoo'].$required."</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_yahoo")."'>";
	echo "<input type='text' id='user_yahoo' name='user_yahoo' value='".$user_yahoo."' maxlength='100' class='textbox' style='width:200px;' />";
	echo "</td>\n</tr>\n";

	if ($required) { $this->setRequiredJavaScript("user_yahoo", $locale['uf_yahoo_error']); }

// Display in profile
} elseif ($profile_method == "display") {
	if ($user_data['user_yahoo']) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['uf_yahoo']."</td>\n";
		echo "<td align='right' class='tbl1'>".$user_data['user_yahoo']."</td>\n";
		echo "</tr>\n";
	}

// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_yahoo']) && ($_POST['user_yahoo'] != "" || $this->_isNotRequired("user_yahoo"))) {
		// Set update or insert user data
		$this->_setDBValue("user_yahoo", stripinput($_POST['user_yahoo']));
	} else {
		$this->_setError("user_yahoo", $locale['uf_yahoo_error'], true);
	}
}
?>
