<?php
include __DIR__ . '/../config/database.php';

mysqli_query($conn, "UPDATE satona SET
    nama_aset='{$_POST['nama_aset']}',
    tanggal_perolehan='{$_POST['tanggal_perolehan']}',
    harga_perolehan='{$_POST['harga_perolehan']}'
    WHERE kode_aset='{$_POST['kode_aset']}'
");
?>