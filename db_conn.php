<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "one_piece_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function getLowestAvailableId($conn) {
    $sql = "SELECT MIN(t1.id + 1) AS id FROM episodes t1 LEFT JOIN episodes t2 ON t1.id + 1 = t2.id WHERE t2.id IS NULL";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['id'] ?? 1;
}
?>