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
require_once "jdf.php";

// Get php version
$phpver = phpversion();

// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	if ((isset($_POST) == true) && (is_array($_POST) == true)) extract($_POST, EXTR_OVERWRITE);
	if ((isset($_GET) == true) && (is_array($_GET) == true)) extract($_GET, EXTR_OVERWRITE);
}

// Start Output Buffering
ob_start();

// Locate config.php and set the basedir path
$folder_level = "";
while (!file_exists($folder_level."config.php")) { $folder_level .= "../"; }
require_once $folder_level."config.php";
define("BASEDIR", $folder_level);

// Establish mySQL database connection
$link = dbconnect($db_host, $db_user, $db_pass, $db_name);

// Fetch the Site Settings from the database and store them in the $settings variable
$settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

// Sanitise $_SERVER globals
$_SERVER['PHP_SELF'] = cleanurl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);

// Common definitions
define("IN_FUSION", TRUE);
define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// Path definitions
define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("IMAGES_A", IMAGES."articles/");
define("IMAGES_N", IMAGES."news/");
define("IMAGES_NC", IMAGES."news_cats/");
define("INCLUDES", BASEDIR."includes/");
define("LOCALE", BASEDIR."locale/");
define("LOCALESET", $settings['locale']."/");
define("FORUM", BASEDIR."forum/");
define("INFUSIONS", BASEDIR."modules/");
define("PHOTOS", IMAGES."photoalbum/");
define("THEMES", BASEDIR."themes/");
define("ADVERTISING", INFUSIONS."advertising/");
define("ADVERTISING_CATEGORIES", INFUSIONS."advertising/images/categories/");
define("ADVERTISING_CLASSIFIEDS", INFUSIONS."advertising/images/classifieds/");

define("ADVERTISING_ADMIN", INFUSIONS."advertising/administration/");

// MySQL database functions
function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbcount($field,$table,$conditions="") {
	$cond = ($conditions ? " WHERE ".$conditions : "");
	$result = @mysql_query("SELECT Count".$field." FROM ".DB_PREFIX.$table.$cond);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		$rows = mysql_result($result, 0);
		return $rows;
	}
}

function dbresult($query, $row) {
	$result = @mysql_result($query, $row);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = @mysql_num_rows($query);
	return $result;
}

function dbarray($query) {
	$result = @mysql_fetch_assoc($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbarraynum($query) {
	$result = @mysql_fetch_row($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:tahoma;font-size:11px;text-align:center;'><b>قادر به ارتباط با بانک اطلاعاتی نمی باشید</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:tahoma;font-size:11px;text-align:center;'><b>قادر به انتخاب بانک اطلاعاتی نمی باشید</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	}
	@mysql_query("SET NAMES utf8", $db_connect);
    @mysql_query( "SET CHARACTER SET utf8", $db_connect );
}

//Add Counter
$result=dbquery("UPDATE ".$db_prefix."settings SET counter=counter+1");

// View Counter
$result = mysql_query("SELECT counter FROM ".$db_prefix."settings");
		$row = mysql_fetch_array($result);
		$stat = ($row['counter']);

// Initialise the $locale array
$locale = array();

// Load the Global language file
include LOCALE.LOCALESET."global.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(USER_IP,0,strlen(USER_IP)-strlen(strrchr(USER_IP,".")));
$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
if (dbcount("(*)", "blacklist", "blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
	header("Location: http://www.google.com/"); exit;
}

// user cookie functions
if (!isset($_COOKIE['general_cms_visited'])) {
	$result=dbquery("UPDATE ".$db_prefix."settings SET counter=counter+1");
	setcookie("general_cms_visited", "yes", time() + 31536000, "/", "", "0");
}

if (isset($_POST['login'])) {
	$user_pass = md5($_POST['user_pass']);
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name' AND (user_password='".md5($user_pass)."' OR user_password='$user_pass')");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		if ($data['user_password'] == $user_pass) {
			$result = dbquery("UPDATE ".$db_prefix."users SET user_password='".md5($user_pass)."' WHERE user_id='".$data['user_id']."'");
		}
		$cookie_value = $data['user_id'].".".$user_pass;
		if ($data['user_status'] == 0) {	
			$cookie_exp = isset($_POST['remember_me']) ? time() + 3600*24*30 : time() + 3600*3;
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("general_cms_user", $cookie_value, $cookie_exp, "/", "", "0");
			redirect(BASEDIR."index.php?user=".$data['user_name'], "header");
		} elseif ($data['user_status'] == 1) {
			redirect(BASEDIR."index.php?error=1", "header");
		} elseif ($data['user_status'] == 2) {
			redirect(BASEDIR."index.php?error=2", "header");
		}
	} else {
		redirect(BASEDIR."index.php?error=3");
	}
}

if (isset($_COOKIE['general_cms_user'])) {
	$cookie_vars = explode(".", $_COOKIE['general_cms_user']);
	$cookie_1 = isNum($cookie_vars['0']) ? $cookie_vars['0'] : "0";
	$cookie_2 = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$cookie_1' AND user_password='".md5($cookie_2)."'");
	unset($cookie_vars,$cookie_1,$cookie_2);
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		if ($userdata['user_status'] == 0) {
			if ($userdata['user_theme'] != "Default" && file_exists(THEMES.$userdata['user_theme']."/theme.php")) {
				define("THEME", THEMES.$userdata['user_theme']."/");
			} else {
				define("THEME", THEMES.$settings['theme']."/");
			}
			if (empty($_COOKIE['general_cms_lastvisit'])) {
				setcookie("general_cms_lastvisit", $userdata['user_lastvisit'], time() + 3600, "/", "", "0");
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_COOKIE['general_cms_lastvisit'];
			}
		} else {
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("general_cms_user", "", time() - 7200, "/", "", "0");
			setcookie("general_cms_lastvisit", "", time() - 7200, "/", "", "0");
			redirect(BASEDIR."index.php", "header");
		}
	} else {
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		setcookie("general_cms_user", "", time() - 7200, "/", "", "0");
		setcookie("general_cms_lastvisit", "", time() - 7200, "/", "", "0");
		redirect(BASEDIR."index.php", "header");
	}
} else {
	define("THEME", THEMES.$settings['theme']."/");
	$userdata = "";	$userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}

// Login & Logout Function
if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
	setcookie("general_cms_user", "", time() - 7200, "/");
	setcookie("general_cms_lastvisit", "", time() - 7200, "/");
	redirect($PHP_SELF, "header");
} else {
	if ($error == 1) {
		echo "<script>alert('".$locale['194']."');</script>";
	} elseif ($error == 2) {
		echo "<script>alert('".$locale['195']."');</script>";
	} elseif ($error == 3) {
		echo "<script>alert('".$locale['196']."');</script>";
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
		if (isset($_COOKIE['general_cms_user'])) {
			$cookie_vars = explode(".", $_COOKIE['general_cms_user']);
			$user_pass = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
			$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($user));
			if (!dbcount("(user_id)", "users", "user_name='".$user_name."' AND user_password='".md5($user_pass)."'")) {
			} else {
				$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
				redirect($PHP_SELF, "header");
			}
		}
	}
}

// Redirect browser using the header function
function redirect($location, $type="header") {
	if ($type == "header") {
		header("Location: ".$location);
	} else {
		echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
	}
}

// Fallback to safe area in event of unauthorised access
function fallback($location) {
	header("Location: ".$location);
	exit;
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
	$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// stripslash function, only stripslashes if magic_quotes_gpc is on
function stripslash($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	return $text;
}

// stripslash function, add correct number of slashes depending on quotes_gpc
function addslash($text) {
	if (!QUOTES_GPC) {
		$text = addslashes(addslashes($text));
	} else {
		$text = addslashes($text);
	}
	return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
	$search = array("&", "\"", "'", "\\", "<", ">");
	$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Validate numeric input
function isNum($value) {
	return (preg_match("/^[0-9]+$/", $value));
}

// This function sanitises news & article submissions
function descript($text,$striptags=true) {
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0;$i < $entities;$i++) $text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	// Kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
        //<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
	        	$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

// Scan image files for malicious code
function verify_image($file) {
	$txt = file_get_contents($file);
	$image_safe = true;
	if (preg_match('#&(quot|lt|gt|nbsp);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
	elseif (preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
	return $image_safe;
}

// Captcha routines
function make_captcha() {
	global $settings;
	srand((double)microtime() * 1000000);
	$temp_num = md5(rand(0,9999));
	$captcha_string = substr($temp_num, 17, 5);
	$captcha_encode = md5($temp_num);
	$result = mysql_query("INSERT INTO ".DB_PREFIX."captcha (captcha_datestamp, captcha_ip, captcha_encode, captcha_string) VALUES('".time()."', '".USER_IP."', '$captcha_encode', '$captcha_string')");
	if ($settings['validation_method'] == "image") {
		return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."'><img src='".INCLUDES."captcha_include.php?captcha_code=".$captcha_encode."' alt='' />\n";
	} else {
		return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."'><strong>".$captcha_string."</strong>\n";
	}
}

function check_captcha($captchs_encode, $captcha_string) {
	if (preg_match("/^[0-9a-z]+$/", $captchs_encode) && preg_match("/^[0-9a-z]+$/", $captcha_string)) {
		$result = dbquery("SELECT * FROM ".DB_PREFIX."captcha WHERE captcha_ip='".USER_IP."' AND captcha_encode='".$captchs_encode."' AND captcha_string='".$captcha_string."'");
		if (dbrows($result)) {
			$result = dbquery("DELETE FROM ".DB_PREFIX."captcha WHERE captcha_ip='".USER_IP."' AND captcha_encode='".$captchs_encode."' AND captcha_string='".$captcha_string."'");
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
		$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0;$i < count($word_list);$i++) {
			if ($word_list[$i] != "") $text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
		}
	}
	return $text;
}

// Display the user's level
function getuserlevel($userlevel) {
	global $locale;
	if ($userlevel==101) { return $locale['user1']; }
	elseif ($userlevel==102) { return $locale['user2']; }
	elseif ($userlevel==103) { return $locale['user3']; }
}

// Check if Administrator has correct rights assigned
function checkrights($right) {
	if (iADMIN && in_array($right, explode(".", iUSER_RIGHTS))) {
		return true;
	} else {
		return false;
	}
}

// Check if user is assigned to the specified user group
function checkgroup($group) {
	if (iSUPERADMIN) { return true; }
	elseif (iADMIN && ($group == "0" || $group == "101" || $group == "102")) { return true; }
	elseif (iMEMBER && ($group == "0" || $group == "101")) { return true; }
	elseif (iGUEST && $group == "0") { return true; }
	elseif (iMEMBER && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

// Compile access levels & user group array
function getusergroups() {
	global $locale;
	$groups_array = array(
		array("0", $locale['user0']),
		array("101", $locale['user1']),
		array("102", $locale['user2']),
		array("103", $locale['user3'])
	);
	$gsql = dbquery("SELECT group_id,group_name FROM ".DB_PREFIX."user_groups");
	while ($gdata = dbarray($gsql)) {
		array_push($groups_array, array($gdata['group_id'], $gdata['group_name']));
	}
	return $groups_array;
}

// Get the name of the access level or user group
function getgroupname($group) {
	global $locale;
	if ($group == "0") { return $locale['user0']; }
	elseif ($group == "101") { return $locale['user1']; }
	elseif ($group == "102") { return $locale['user2']; }
	elseif ($group == "103") { return $locale['user3'];
	} else {
		$gsql = dbquery("SELECT group_id,group_name FROM ".DB_PREFIX."user_groups WHERE group_id='$group'");
		if (dbrows($gsql)!=0) {
			$gdata = dbarray($gsql);
			return $gdata['group_name'];
		} else {
			return "N/A";
		}
	}
}

function groupaccess($field) {
	if (iSUPERADMIN) { $res = "($field='0' OR $field='101' OR $field='102' OR $field='103'";
	} elseif (iADMIN) { $res = "($field='0' OR $field='101' OR $field='102'";
	} elseif (iMEMBER) { $res = "($field='0' OR $field='101'";
	} elseif (iGUEST) { $res = "($field='0'"; }
	if (iUSER_GROUPS != "") $res .= " OR $field='".str_replace(".", "' OR $field='", iUSER_GROUPS)."'";
	$res .= ")";
	return $res;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected="") {
	$res = "";
	for ($i=0;$i < count($files);$i++) {
		$sel = ($selected == $files[$i] ? " selected" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}

// Universal page pagination function by CrappoMan
function makepagenav($start,$count,$total,$range=0,$link=""){
	global $locale;
	if ($link == "") $link = FUSION_SELF."?";
	$res="";
	$pg_cnt=ceil($total / $count);
	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$res.="<table cellspacing='1' cellpadding='1' border='0' class='tbl-border'>\n<tr>\n";
		$res.="<td class='tbl2'><span class='small'>".$locale['052']."$cur_page".$locale['053']."$pg_cnt</span></td>\n";
		if ($idx_back >= 0) {
			if ($cur_page > ($range + 1)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=0'>&lt;&lt;</a></td>\n";
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_back'>&lt;</a></td>\n";
		}
		$idx_fst=max($cur_page - $range, 1);
		$idx_lst=min($cur_page + $range, $pg_cnt);
		if ($range==0) {
			$idx_fst = 1;
			$idx_lst=$pg_cnt;
		}
		for($i=$idx_fst;$i<=$idx_lst;$i++) {
			$offset_page=($i - 1) * $count;
			if ($i==$cur_page) {
				$res.="<td class='tbl1'><span class='small'><b>$i</b></span></td>\n";
			} else {
				$res.="<td class='tbl1'><a class='small' href='$link"."rowstart=$offset_page'>$i</a></td>\n";
			}
		}
		if ($idx_next < $total) {
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_next'>&gt;</a></td>\n";
			if ($cur_page < ($pg_cnt - $range)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=".($pg_cnt-1)*$count."'>&gt;&gt;</a></td>\n";
		}
		$res.="</tr>\n</table>\n";

	}
	return $res;
}

// Format the date & time accordingly
function showdate($format, $val) {
	global $settings;
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate") {
		return jdate($settings[$format], $val+($settings['timeoffset']*3600));
	} else {
		return jdate($format, $val+($settings['timeoffset']*3600));
	}
}

// Translate bytes into kb, mb, gb or tb by CrappoMan
function parsebytesize($size,$digits=2,$dir=false) {
	$kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
	if (($size==0)&&($dir)) { return "Empty"; }
	elseif ($size<$kb) { return $size."بایت"; }
	elseif ($size<$mb) { return round($size/$kb,$digits)."کیلوبایت"; }
	elseif ($size<$gb) { return round($size/$mb,$digits)."مگابایت"; }
	elseif ($size<$tb) { return round($size/$gb,$digits)."گیگابایت"; }
	else { return round($size/$tb,$digits)."ترابایت"; }
}

// User level, Admin Rights & User Group definitions
define("iGUEST",$userdata['user_level'] == 0 ? 1 : 0);
define("iMEMBER", $userdata['user_level'] >= 101 ? 1 : 0);
define("iADMIN", $userdata['user_level'] >= 102 ? 1 : 0);
define("iSUPERADMIN", $userdata['user_level'] == 103 ? 1 : 0);
define("iUSER", $userdata['user_level']);
define("iUSER_RIGHTS", $userdata['user_rights']);
define("iUSER_GROUPS", substr($userdata['user_groups'], 1));

if (iADMIN) {
	define("iAUTH", substr($userdata['user_password'],16,32));
	$aidlink = "?aid=".iAUTH;
}
?>