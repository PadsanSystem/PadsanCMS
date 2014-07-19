<?php
/*
|--------------------------------|
|		Padsan System CMS		 |
|--------------------------------|
|		Advertising Version		 |
|--------------------------------|
|Web   : www.padsansystem.com	 |
|Email : cms@padsansystem.com	 |
|Tel   : +98 - 261 2533135		 |
|Fax   : +98 - 261 2533136		 |
|--------------------------------|
*/
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";
if (file_exists(BASEDIR."favicon.png")){
	echo "<link rel='icon' href='".BASEDIR."favicon.png' type='image/x-icon'>\n";
}
// theme settings
$body_text = "#555555";
$body_bg = "#FEFEFE";
$theme_width = "900px";
$theme_width_l = "200px";
$theme_width_r = "175px";

// Theme Selector
if (!isset($_COOKIE['general_cms_style'])){
	setcookie("general_cms_style", "BlueTheme", time() + 76200, "/");
	$theme = "styles_0.css";
}
if ($_COOKIE['general_cms_style'] == "BlueTheme"){
	$theme = "styles_0.css";
	$banner = "".THEMES."default/images/blue_banner.jpg";
}elseif ($_COOKIE['general_cms_style'] == "RedTheme"){
	$theme = "styles_1.css";
}elseif ($_COOKIE['general_cms_style'] == "GreenTheme"){
	$theme = "styles_2.css";
}

// Function Render_header
function render_header($header_content) {

global $theme_width,$settings;

$banner = "".THEMES."default/images/banner.jpg";
echo "<table align='center' cellspacing='0' cellpadding='0' width='$theme_width' class='body'>
<tr><td><table cellpadding='0' cellspacing='0' width='100%'><tr>
<td><table align='center' cellpadding='0' cellspacing='0' width='100%'><tr>
<td><div align='right' id='clockbar'><div class='clockbarcolor'>
<div id='Clock'></div>".showsubdate()."</div>
<div align='right' id='pageheader'>
<link rel='alternate stylesheet' type='text/css' href='".THEME."styles_0.css' title='BlueTheme'/>
<link rel='alternate stylesheet' type='text/css' href='".THEME."styles_1.css' title='RedTheme'/>
<link rel='alternate stylesheet' type='text/css' href='".THEME."styles_2.css' title='GreenTheme'/>
&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"setActiveStyleSheet('BlueTheme'); return false;\"><img src='".THEME."images/blue.jpg' border='0'/></a>
<a href='#' onclick=\"setActiveStyleSheet('RedTheme'); return false;\"><img src='".THEME."images/red.jpg' border='0'/></a>
<a href='#' onclick=\"setActiveStyleSheet('GreenTheme'); return false;\"><img src='".THEME."images/green.jpg' border='0'/></a>
</div>
<table cellSpacing='0' cellPadding='0' border='0' width='100%'>
<tr>
<td class='border-subheader'><a href='http://".$settings['siteurl']."'><img src='".$banner."' border='0' width='100%' alt='".$settings['sitename']."'/></a></td>
</tr>
</table>
</div>
</td></tr>
</table>
</td></tr>
</table>
</td></tr>
</table>
</td></tr>
</table>
<table align='center' width='$theme_width' cellSpacing='0' cellPadding='0' border='0'>
<tr>
<td class='subheader' align='right'>";
echo showsublinks();
echo"
</td>
<table cellpadding='0' cellspacing='0' width='$theme_width' align='center'>\n<tr>\n";
}

function render_footer($license=true) {

global $theme_width,$settings,$locale;

	echo "</tr>\n</table></td></tr>

</td></tr>
</table><br/>\n";
}

function render_news($subject, $news, $info) {
$subject = strip_tags($subject);
global $theme_width;
	echo "<table cellSpacing='0' cellPadding='2' width='100%' border='0'><tr>
<td class='tableHeadingBG'><div class='tableHeading'>$subject</div>
</td></tr>
<tr><td class='td-cell1' style='WIDTH: 100%' vAlign='top'>$news</td></tr>
<tr><td class='td-cell2' align='center' style='WIDTH: 100%'>
<table cellSpacing='0' cellPadding='0' border='0'><tr>
<td align='center'>";
	echo openform("N",$info['news_id']).newsposter($info," &middot;").newsopts($info," &middot;").closeform("N",$info['news_id']);
	echo "</td>
</tr>
</table>
</td></tr>
</table>\n";
}

function render_article($subject, $article, $info) {
	
	echo "<table style='WIDTH: 100%;' cellSpacing='0' cellPadding='2' border='0'><tr>
<td class='tableHeadingBG'><div class='tableHeading'>$subject</div></td></tr>
<tr><td class='td-cell1' style='WIDTH: 100%' vAlign='top'>".$article."</td>
</tr>
<tr><td class='td-cell2' align='center' style='WIDTH: 100%'>
<table cellSpacing='0' cellPadding='0' border='0'><tr><td align='center'>";
	echo openform("A",$info['article_id']).articleposter($info," &middot;").articleopts($info," &middot;").closeform("A",$info['article_id']);
	if (iGuest){
		tablebreak();
	}
	if ($info['article_files'] != NULL){
		echo "<br><a href=".$info['article_files']." class='submit'>دریافت فایل مرتبط</a>";
		tablebreak();
	}
	echo "</td>
</tr>
</table>
</td></tr>
</table>\n";
}

function opentable($title) {

	echo "<table style='WIDTH: 100%' cellSpacing='0' cellPadding='0' border='0'><tr>
<td class='tableHeadingBG'>
<div class='tableHeading'>$title</div></td>
</tr>
<tr><td class='td-cell1' valign='top'>";
}

function closetable() {

	echo "</td></tr>
</table>\n";
}

function openside($title) {
	
	echo "<table style='WIDTH: 100%' cellSpacing='0' cellPadding='0' border='0'><tr>
<td class='tableHeadingBG'>
<div class='tableHeading'>$title</div></td>
</tr>
<tr><td class='td-cell1' vAlign='top'>";
}

function closeside() {

	echo "</td></tr>
</table>\n";
	tablebreak();
}

function opensidex($title,$state="on") {

$boxname = str_replace(" ", "", $title);
echo "<table style='WIDTH: 100%' cellSpacing='0' cellPadding='0' border='0'><tr>
<td class='tableHeadingBG'>
<div class='tableHeading'>$title</div></td>
<td align='left' class='tableHeadingBG'>".panelbutton($state,$boxname)."</td>
</tr>
<tr><td class='td-cell1' vAlign='top' colspan='2'>
<div id='box_$boxname'".($state=="off"?" style='display:none'":"").">\n";
}

function closesidex() {

	echo "</div></td></tr>
</table>\n";
	tablebreak();
}

function tablebreak() {

	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr>\n<td height='5'></td>\n</tr>\n</table>\n";
}
?>