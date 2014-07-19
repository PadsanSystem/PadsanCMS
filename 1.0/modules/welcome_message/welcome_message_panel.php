<?php
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }

opentable($locale['024']);
echo stripslashes($settings['siteintro'])."\n";
closetable();
?>