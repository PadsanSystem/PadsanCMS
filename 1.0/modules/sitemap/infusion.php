<?php
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location: ../../index.php"); exit; }

if (!defined("LANGUAGE")) {
	define("LANGUAGE", $settings['locale']);
}
	// Load the infusion's default locale file.
	include INFUSIONS."sitemap/locale/Persian.php";

// Infusion general information
$inf_title = "نقشه ي سايت";
$inf_description = $locale['inv101'];
$inf_version = "1.0";
$inf_developer = "Padsanan System";
$inf_email = "development@padsanansystem.com";
$inf_weburl = "padsanansystem.com";

$inf_link_name = $locale['SM100'];
$inf_link_url = "index.php"; 
$inf_link_visibility = "0";

?>