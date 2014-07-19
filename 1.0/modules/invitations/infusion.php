<?php
/*
|--------------------------------|
|		Padsan System CMS		 |
|--------------------------------|
|		General Version			 |
|--------------------------------|
|Web   : www.PadsanCMS.com		 |
|Email : Support@PadsanCMS.com	 |
|Tel   : +98 - 261 2533135		 |
|Fax   : +98 - 261 2533136		 |
|--------------------------------|
*/
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location: ../../index.php"); exit; }

// Load the infusion's default locale file.
include INFUSIONS."invitations/locale/".$settings['locale'].".php";

// Infusion general information
$inf_title = $locale['inv100'];
$inf_description = $locale['inv101'];
$inf_version = "1.0";
$inf_developer = "Padsanan System";
$inf_email = "development@padsanansystem.com";
$inf_weburl = "padsanansystem.com";


$inf_link_name = $locale['inv102']; // if not required replace $locale['inv102']; with "";
$inf_link_url = "index.php"; // The filename you wish to link to.
$inf_link_visibility = "101"; // 0 - Guest / 101 - Member / 102 - Admin / 103 - Super Admin.

$inf_newtables = 1; // Number of new db tables to create or drop.
$inf_insertdbrows = 1; // Numbers rows added into created db tables.
$inf_altertables = 0; // Number of db tables to alter (upgrade).
$inf_deldbrows = 0; // Number of db tables to delete data from.

// Delete any items not required here.

$inf_newtable_[1] = "invite_settings (
  inv_group SMALLINT(3) UNSIGNED NOT NULL DEFAULT 101,
  inv_lasts SMALLINT(3) UNSIGNED NOT NULL DEFAULT 10,
  inv_adminapproval BOOL NOT NULL DEFAULT 0,
  inv_emailverification BOOL NULL DEFAULT 0,
  inv_enabled BOOL NULL default 1,
  PRIMARY KEY(inv_group),
  INDEX fusion_invite_settings_FKIndex1(inv_group)
) TYPE=MyISAM;";

$inf_droptable_[1] = "invite_settings";

$inf_insertdbrow_[1] = "invite_settings (inv_group, inv_lasts, inv_adminapproval) VALUES(101, 10, 0)";

//$inf_altertable_[1] = "table_name ADD etc";

//$inf_deldbrow_[1] = "other_table";

?>