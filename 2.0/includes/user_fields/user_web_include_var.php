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

// Version of the user fields api
$user_field_api_version = "1.01.00";

$user_field_name = $locale['uf_web'];
$user_field_desc = $locale['uf_web_desc'];
$user_field_dbname = "user_web";
$user_field_group = 1;
$user_field_dbinfo = "VARCHAR(200) NOT NULL DEFAULT ''";
?>