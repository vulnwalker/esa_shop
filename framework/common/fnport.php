<?php
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$host_name=parse_url($url, PHP_URL_HOST);
$host_port=parse_url($url, PHP_URL_PORT);
/*
echo "Host : $host_name<br>";
echo "Port : $host_port<br>";
*/
$Main->host_port=$host_port;
if ($Main->host_port=='5555')
{
	include ('vars_demo.php');
	
	
} 
elseif ($Main->host_port=='8895')
{
	include ('vars_garut_kab.php');

}
elseif ($Main->host_port=='8896')
{
	include ('vars_cirebon_kab.php');

}
elseif ($Main->host_port=='8897')
{
	include ('vars_sumedang_kab.php');

}
elseif ($Main->host_port=='8893')
{

include ('vars_serang_kab.php');
}
elseif ($Main->host_port=='8892')
{

include ('vars_tasik_kab.php');
}
else
{
	include ('vars_def.php');
	
}

?>



