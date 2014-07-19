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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once INCLUDES."forum_functions_include.php";
if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id)) fallback("index.php");

$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);

if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) fallback("index.php");
} else {
	fallback("index.php");
}
if (!checkgroup($fdata['forum_posting'])) fallback("index.php");

$forum_mods = explode(".", $fdata['forum_moderators']);
if (iMEMBER && in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }

$caption = $fdata['forum_cat_name']." | ".$fdata['forum_name'];

if ($action == "newthread") {
	include "postnewthread.php";

} elseif ($action == "reply") {
	if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");

	$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { fallback("index.php"); }
	
	if (!$tdata['thread_locked']) { include "postreply.php"; } else { fallback("index.php"); }
} elseif ($action == "edit") {
	if (!isset($thread_id) || !isNum($thread_id) || !isset($post_id) || !isNum($post_id)) { fallback("index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { fallback("index.php"); }

	$result = dbquery("SELECT * FROM ".$db_prefix."posts WHERE post_id='".$post_id."' AND thread_id='".$tdata['thread_id']."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $pdata = dbarray($result); } else { fallback("index.php"); }
	
	if ($userdata['user_id'] != $pdata['post_author'] && !iMOD && !iSUPERADMIN) fallback("index.php");
	
	if (!$tdata['thread_locked']) {
		include "postedit.php";
	} else {
		if (iMOD || iSUPERADMIN) { include "postedit.php"; } else { fallback("index.php"); }
	}
} else {
	header("Location: index.php");
}

echo "</td>";
require_once BASEDIR."footer.php";
?>