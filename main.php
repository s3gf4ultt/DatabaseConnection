<?php

include_once("userinfo.php");
include("sql_manager.php");

$mysql = openConnection();

if (isset($_GET['action']))
	manageGET($mysql, $_GET['action']);
elseif (isset($_POST['action']))
	managePOST($mysql, $_POST['action']);
else
	echo "Could not figure out any request :/\n";
?>