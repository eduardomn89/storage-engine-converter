<?php 

	require_once("config/config.php");
	require_once("classes/Messages.php");
	require_once("classes/Status.php");
	require_once("models/Server.php");
	require_once("models/Mysql_admin.php");

	$mysql = new Mysql_admin();	

	$changeEngine = '';

	if(isset($_POST['convert'])){
		
		$db = $_POST['dataBase'];

		$engineType = $_POST['engine'];
		
		$changeEngine = $mysql -> change_storage_engine($db, $engineType);
		
	}

	$getDB = $mysql -> get_databases();

	$selectOptionsDB = $mysql -> show_select_optionsDB($getDB['data']);

	require_once('views/theme/index_view.php');

?>