<?php
$host = "localhost";
$username = "u458240196_root";
$password = "caresDB2025";
$database = "u458240196_caresDB";

$db = new mysqli($host, $username, $password, $database);
$db->set_charset("utf8");

if ($db->connect_error) {
  trigger_error('Database connection failed: ' . $db->connect_error, E_USER_ERROR);
}

$db->query("SET time_zone = '+08:00'");
?>
