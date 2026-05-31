<?php
$conn = mysqli_connect('localhost', 'root', '', 'chronicle');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>