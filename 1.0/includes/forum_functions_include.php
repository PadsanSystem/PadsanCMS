<?php
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }

$imagetypes = array(
	".bmp",
	".gif",
	".iff",
	".jpg",
	".jpeg",
	".png",
	".psd",
	".tiff",
	".wbmp"
);

function attach_exists($file) {
	$dir = FORUM."attachments/";
	$i = 1;
	$file_name = substr($file, 0, strrpos($file, "."));
	$file_ext = strrchr($file,".");
	while (file_exists($dir.$file)) {
		$file = $file_name."_".$i.$file_ext;
		$i++;
	}
	return $file;
}

if (isset($getfile)) {
	if (!isNum($getfile)) $getfile = 0;
	require_once INCLUDES."class.httpdownload.php";
	$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$getfile'");
	if (dbrows($result)) {
		ob_end_clean();
		$data = dbarray($result);
		$object = new httpdownload;
		$object->set_byfile(FORUM."attachments/".$data['attach_name']);
		$object->use_resume = true;
		$object->download();
	}
	exit;
}
?>