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
include LOCALE.LOCALESET."upgrade.php";

if (!checkrights("U")) fallback("../index.php");

opentable("به روز رساني");
echo "".$locale['upgrade001'].$locale['upgrade002'].$locale['upgrade003'].$locale['upgrade004'].$locale['upgrade005'].$locale['upgrade006'].$locale['upgrade007']."";
closetable();

require_once BASEDIR."footer.php";
?>