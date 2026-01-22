<?php
$conn = mysqli_connect("localhost", "root", "", "db_inventarisir_aset");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}