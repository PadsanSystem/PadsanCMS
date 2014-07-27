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

include LOCALE.LOCALESET."search/weblinks.php";

$form_elements['weblinks']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['weblinks']['disabled'] = array();
$form_elements['weblinks']['display'] = array();
$form_elements['weblinks']['nodisplay'] = array();

$radio_button['weblinks'] = "<label><input type='radio' name='stype' value='weblinks'".($_GET['stype'] == "weblinks" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['w400']."</label>";
?>