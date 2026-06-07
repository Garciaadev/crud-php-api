<?php
$host = 'mysql';
$dbname = 'crud_db';
$user = 'crud_user';
$pass = 'crud_pass';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]));
}

$conn->set_charset('utf8');
?>
