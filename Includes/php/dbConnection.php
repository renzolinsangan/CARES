<?php
$servername = "auth-db445.hstgr.io";
$username = "u458240196_root";
$password = "caresDB2025";
$database = "u458240196_caresDB"; 

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
    die("❌ Connection failed: " . $db->connect_error);
}
?>
