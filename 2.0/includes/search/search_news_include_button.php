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

include LOCALE.LOCALESET."search/news.php";

$form_elements['news']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['news']['disabled'] = array();
$form_elements['news']['display'] = array();
$form_elements['news']['nodisplay'] = array();

$radio_button['news'] = "<label><input type='radio' name='stype' value='news'".($_GET['stype'] == "news" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['n400']."</label>";
?>