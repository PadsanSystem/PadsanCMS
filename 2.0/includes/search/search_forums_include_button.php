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

include LOCALE.LOCALESET."search/forums.php";

$forum_list = ""; $current_cat = "";
$result = dbquery(
   "SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
   FROM ".DB_FORUMS." f
   INNER JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
   WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);

$sel = "";
$forum_list .= "<select name='forum_id' class='textbox'>";
$forum_list .= "<option value='0'$sel>".$locale['f401']."</option>\n";
$rows2 = dbrows($result);
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") $forum_list .= "</optgroup>\n";
		$current_cat = $data2['forum_cat_name'];
		$forum_list .= "<optgroup label='".trimlink($data2['forum_cat_name'],20)."'>\n";
	}
	$sel = ($data2['forum_id'] == $_GET['forum_id'] ? " selected='selected'" : "");
	$forum_list .= "<option value='".$data2['forum_id']."'$sel>".trimlink($data2['forum_name'],20)."</option>\n";
}
if ($rows2) { $forum_list .= "</optgroup>\n"; }
$forum_list .= "</select>";

$form_elements['forums']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['forums']['disabled'] = array();
$form_elements['forums']['display'] = array();
$form_elements['forums']['nodisplay'] = array();

$radio_button['forums'] = "<label><input type='radio' name='stype' value='forums'".($_GET['stype'] == "forums" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['f400']."</label> ".$forum_list;

?>