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

include LOCALE.LOCALESET."search/photos.php";

$form_elements['photos']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['photos']['disabled'] = array();
$form_elements['photos']['display'] = array();
$form_elements['photos']['nodisplay'] = array();

$radio_button['photos'] = "<label><input type='radio' name='stype' value='photos'".($_GET['stype'] == "photos" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['p400']."</label>";
?>