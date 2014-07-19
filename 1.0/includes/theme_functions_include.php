<script type="text/javascript">
				function flipBox(who) {
	var tmp; 
	if (document.images['b_' + who].src.indexOf('_on') == -1) { 
		tmp = document.images['b_' + who].src.replace('_off', '_on');
		document.getElementById('box_' + who).style.display = 'none';
		document.images['b_' + who].src = tmp;
	} else { 
		tmp = document.images['b_' + who].src.replace('_on', '_off');
		document.getElementById('box_' + who).style.display = 'block';
		document.images['b_' + who].src = tmp;
	} 
}
</script>
<?php
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

function showsublinks($sep="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$class="") {
	$i = 0; $res = "";
	$sres = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
	if (dbrows($sres) != 0) {
		while($sdata = dbarray($sres)) {
			if (checkgroup($sdata['link_visibility'])) {
				if ($sdata['link_url']!="---") {
					if ($i != 0) { $res .= " ".$sep."\n"; } else { $res .= "\n"; }
					$link_target = $sdata['link_window'] == "1" ? " target='_blank'" : "";
					$link_class = $class ? " class='$class'" : "";
					if (strstr($sdata['link_url'], "http://") || strstr($sdata['link_url'], "https://")) {
						$res .= "<a href='".$sdata['link_url']."'".$link_target.$link_class.">".$sdata['link_name']."</a>";
					} else {
						$res .= "<a class='subheader-text' href='".BASEDIR.$sdata['link_url']."'".$link_target.$link_class.">".$sdata['link_name']."</a>";
					}
				}
				$i++;
			}
		}
	}
	if ($i != 0) { return $res; } else { return "&nbsp;"; }
}

function showsubdate() {
	global $settings;
	return ucwords(showdate($settings['subheaderdate'], time()));
}

function newsposter($info,$sep="",$class="") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = "<img src='".THEME."images/bullet.gif' alt=''> ";
	$res .= "<a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a> ";
	$res .= $locale['041'].showdate("longdate", $info['news_date']);
	$res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep."\n" : "\n";
	return $res;
}

function newsopts($info,$sep,$class="") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='index.php?readmore=".$info['news_id']."'".$link_class.">".$locale['042']."</a> ".$sep." ";
	if ($info['news_allow_comments']) $res .= "<a href='index.php?readmore=".$info['news_id']."'".$link_class.">".$info['news_comments'].$locale['043']."</a> ".$sep." ";
	if ($info['news_ext'] == "y" || $info['news_allow_comments']) $res .= $info['news_reads'].$locale['044']."\n";
	$res .= $sep." <a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>\n";
	return $res;
}

function articleposter($info,$sep="",$class="") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = "<img src='".THEME."images/bullet.gif' alt=''>\n";
	$res .= $locale['040']."<a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a>\n";
	$res .= $locale['041'].showdate("longdate", $info['article_date']);
	$res .= $info['article_allow_comments'] ? $sep."\n" : "\n";
	return $res;
}

function articleopts($info,$sep) {
	global $locale; $res = "";
	if ($info['article_allow_comments']) $res = $info['article_comments'].$locale['043']." ".$sep."\n";
	$res .= $info['article_reads'].$locale['044']." ".$sep."\n";
	$res .= "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>\n";
	return $res;
}

function openform($item_type,$item_id) {
	global $aidlink;
	$res = "";
	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) $res .= "<form name='editnews".$item_id."' method='post' action='".ADMIN."news.php".$aidlink."&amp;news_id=".$item_id."'>\n";
	} elseif ($item_type == "A") {
		if (iADMIN && checkrights($item_type)) $res .= "<form name='editarticle".$item_id."' method='post' action='".ADMIN."articles.php".$aidlink."&amp;article_id=".$item_id."'>\n";
	}
	return $res;
}

function closeform($item_type,$item_id) {
	global $locale; $res = "";
	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) $res .= "&middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$item_id.".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a>\n</form>\n";
	} elseif ($item_type == "A") {
		if (iADMIN && checkrights($item_type)) $res .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editarticle".$item_id.".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a>\n</form>\n";
	}
	return $res;
}

function panelbutton($state,$bname) {
	return "<img src='".THEME."images/panel_".($state == "on" ? "off" : "on").".gif' name='b_$bname' alt='' onclick=\"javascript:flipBox('$bname')\">";
}
?>