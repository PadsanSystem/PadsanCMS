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

include LOCALE.LOCALESET."search/articles.php";

$form_elements['articles']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['articles']['disabled'] = array();
$form_elements['articles']['display'] = array();
$form_elements['articles']['nodisplay'] = array();

$radio_button['articles'] = "<label><input type='radio' name='stype' value='articles'".($_GET['stype'] == "articles" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['a400']."</label>";
?>