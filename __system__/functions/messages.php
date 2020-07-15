<?php 
	require_once 'connection/conn.php';

	$json["message"] = 1;

	if(isset($_SESSION["msg"])) {
		$json["title"] = $_SESSION["msg"]["title"];
		$json["text"] = $_SESSION["msg"]["text"];
		unset($_SESSION["msg"]);
	} else {
		$json["message"] = 0;
	}

	echo json_encode($json);
?>