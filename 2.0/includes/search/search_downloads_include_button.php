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

include LOCALE.LOCALESET."search/downloads.php";

$form_elements['downloads']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['downloads']['disabled'] = array();
$form_elements['downloads']['display'] = array();
$form_elements['downloads']['nodisplay'] = array();

$radio_button['downloads'] = "<label><input type='radio' name='stype' value='downloads'".($_GET['stype'] == "downloads" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['d400']."</label>";
?>