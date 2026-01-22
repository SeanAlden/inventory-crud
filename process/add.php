<?php
include __DIR__ . '/../config/database.php';

if ($_POST) {
    mysqli_query($conn, "INSERT INTO satona VALUES(
        NULL,
        '{$_POST['nama_aset']}',
        '{$_POST['tanggal_perolehan']}',
        '{$_POST['harga_perolehan']}'
    )");
}
?>