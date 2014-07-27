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
require_once "maincore.php";

if (isset($_GET['article_id']) && isnum($_GET['article_id'])) {
	redirect("articles.php?article_id=".$_GET['article_id']);
}
?>