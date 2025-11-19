<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ievziedi_db";

// Izveido savienojumu
$conn = new mysqli($servername, $username, $password, $database);

// Pārbauda savienojumu
if ($conn->connect_error) {
    die("Savienojuma kļūda: " . $conn->connect_error);
}   
