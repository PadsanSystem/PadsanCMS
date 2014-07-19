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
require_once "maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
if (iMEMBER) {
	header("Location:index.php");
} else {
	opentable($locale['060']);
	echo "<div align='center'>
<form name='loginform' method='post' action='".FUSION_SELF."'>
".$locale['061']."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".$locale['062']."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y'>".$locale['063']."<br><br>
<input type='submit' name='login' value='Login' class='button'><br>
</form>
<br/>
</div>\n";
	closetable();
}
require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>