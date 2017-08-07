<?php
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$host_name=parse_url($url, PHP_URL_HOST);
$host_port=parse_url($url, PHP_URL_PORT);
echo "Host : $host_name<br>";
echo "Port : $host_port<br>";
?>



