<?php
$host = "localhost";
$dbname = "itask_db";
$username = "root";
$password = ""; // Change this if your MySQL has a password.

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("We can't reach the database right now. Make sure MySQL is running, then refresh this page.");
}
?>
