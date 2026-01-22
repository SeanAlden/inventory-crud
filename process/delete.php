<?php
include __DIR__ . '/../config/database.php';

mysqli_query($conn, "DELETE FROM satona WHERE kode_aset='{$_GET['id']}'");
?>