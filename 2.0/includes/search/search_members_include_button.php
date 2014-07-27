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

include LOCALE.LOCALESET."search/members.php";

$form_elements['members']['enabled'] = array("order1", "order2");
$form_elements['members']['disabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "chars");
$form_elements['members']['display'] = array();
$form_elements['members']['nodisplay'] = array();

$radio_button['members'] = "<label><input type='radio' name='stype' value='members'".($_GET['stype'] == "members" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['m400']."</label>";
?>