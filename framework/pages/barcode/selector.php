<?php

$SPg = $_GET['SPg'];
switch ($SPg){
	case 'status' : include('status.php'); break;
	case 'data' : include('data.php'); break;
	case 'update' : include('update.php'); break;
	
	default : echo 'tes'; break;
}

?>