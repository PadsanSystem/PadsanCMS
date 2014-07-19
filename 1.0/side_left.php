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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

// Display left side panels
$p_res = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' AND panel_status='1' ORDER BY panel_order");
if (dbrows($p_res) != 0) {
	$pc = 0;
	while ($p_data = dbarray($p_res)) {
		if (checkgroup($p_data['panel_access'])) {
			if ($pc == 0) echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
			if ($p_data['panel_type'] == "file") {
				$panel_name = $p_data['panel_filename'];
				include INFUSIONS.$panel_name."/".$panel_name."_panel.php";
			} else {
				eval(stripslashes($p_data['panel_content']));
			}
			$pc++;
		}
	}
	if ($pc > 0) echo "</td>\n";
}

echo "<td valign='top' class='main-bg'>\n";
if (file_exists(BASEDIR."setup.php")) echo "<center>".$locale['198']."</center><br>";

$p_res = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' AND panel_status='1' ORDER BY panel_order");
if (dbrows($p_res) != 0) {
	while ($p_data = dbarray($p_res)) {
		if (checkgroup($p_data['panel_access'])) {
			if ($p_data['panel_display'] == 1 || eregi($settings['opening_page']."$", FUSION_REQUEST.(FUSION_QUERY ? "?".FUSION_QUERY : ""))) {
				if ($p_data['panel_type'] == "file") {
					$panel_name = $p_data['panel_filename'];
					include INFUSIONS.$panel_name."/".$panel_name."_panel.php";
				} else {
					eval(stripslashes($p_data['panel_content']));
				}
				tablebreak();
			}
		}
	}
}
?>