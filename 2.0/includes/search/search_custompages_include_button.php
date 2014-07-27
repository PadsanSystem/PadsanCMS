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

include LOCALE.LOCALESET."search/custompages.php";

$form_elements['custompages']['enabled'] = array("fields1", "fields2", "fields3", "order1", "order2", "chars");
$form_elements['custompages']['disabled'] = array("datelimit", "sort");
$form_elements['custompages']['display'] = array();
$form_elements['custompages']['nodisplay'] = array();

$radio_button['custompages'] = "<label><input type='radio' name='stype' value='custompages'".($_GET['stype'] == "custompages" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['c400']."</label>";
?>