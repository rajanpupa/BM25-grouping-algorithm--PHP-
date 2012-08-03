<?php
$databaseName="nepalwatch";
$dbUserName="nepalwatch";
$dbPassWord="nepalwatch";



$db=mysql_connect('localhost',$dbUserName,$dbPassWord);
mysql_select_db($databaseName,$db);//database name, above variable
?>
