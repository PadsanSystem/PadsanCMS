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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
echo "<div style='position:absolute'>";
include LOCALE.LOCALESET."admin/news-articles.php";
echo "</div>";

if (!checkRights("NC") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

if (isset($action) && $action == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='$cat_id'");
	if (dbrows($result) != 0) {
		opentable($locale['430']);
		echo "<center><b>".$locale['431']."</b><br>\n<span class='small'>".$locale['432']."</span></center>\n";
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."news_cats WHERE news_cat_id='$cat_id'");
		opentable($locale['430']);
		echo "<center><b>".$locale['433']."</b></center>\n";
	}
	closetable();
	tablebreak();
}
if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	$cat_image = stripinput($_POST['cat_image']);
	if ($cat_name != "" && $cat_image != "") {
		if ($action == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."news_cats SET news_cat_name='$cat_name', news_cat_image='$cat_image' WHERE news_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('$cat_name', '$cat_image')");
		}
	}
	redirect(FUSION_SELF.$aidlink);
}
if (isset($action) && $action == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = $data['news_cat_name'];
	$cat_image = $data['news_cat_image'];
	$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['news_cat_id'];
	opentable($locale['434']);
} else {
	$cat_name = "";
	$cat_image = "";
	$formaction = FUSION_SELF.$aidlink;
	opentable($locale['435']);
}
$image_files = makefilelist(IMAGES_NC, ".|..|index.php", true);
$image_list = makefileopts($image_files,$cat_image);
echo "<form name='addcat' method='post' action='$formaction'>
<table align='center' cellpadding='0' cellspacing='5' width='100%' class='textbox'>
<tr>
<td width='130' class='tbl'>".$locale['436']."</td>
<td class='tbl'><input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='130' class='tbl'>".$locale['437']."</td>
<td class='tbl'><select name='cat_image' class='textbox' style='width:200px;'>
$image_list</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><input type='submit' name='save_cat' value='".$locale['438']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();
tablebreak();
opentable($locale['440']);
$result = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_name");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4; 
	echo "<table align='center' cellpadding='0' cellspacing='5' width='100%' class='textbox'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
		echo "<td align='center' width='25%' class='tbl'><b>".$data['news_cat_name']."</b><br>\n";
		if (file_exists(IMAGES_NC.$data['news_cat_image'])) {
			echo "<img src='".IMAGES_NC.$data['news_cat_image']."' alt='".$data['news_cat_name']."'><br>\n";
		} else {
			echo "<img src='".IMAGES."imagenotfound.jpg' alt='".$data['news_cat_name']."'><br>\n";
		}
		echo "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['news_cat_id']."'>".$locale['441']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;cat_id=".$data['news_cat_id']."'>".$locale['442']."</a></span></td>\n";
		$counter++;
	}
	echo "</tr>\n</table>\n";
} else {
	echo "<center><br>\n".$locale['443']."<br><br>\n</center>\n";
}
echo "<center><br>\n<a href='".ADMIN."images.php".$aidlink."&amp;ifolder=imagesnc'>".$locale['439']."</a><br><br>\n</center>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>