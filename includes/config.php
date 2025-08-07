<?php
// config.php

$host = 'localhost:3306';
$db = 'tccine';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if (!$con = mysqli_connect($host, $user, $pass, $db)) {
  die("Impossível conectar.");
}

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  exit('DB Connection failed: ' . $e->getMessage());
}
