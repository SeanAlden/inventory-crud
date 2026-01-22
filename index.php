<?php
// --- 1. PROTEKSI HALAMAN (Cek Login) ---
require_once 'config/database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AsetController.php';

$auth = new AuthController($conn);
$auth->checkAuth(); // Redirect ke login jika belum login

// --- LOGIKA CRUD (Kode asli anda tetap disini) ---
// (Pastikan logika PHP untuk query select/insert/update/delete ada di atas sini seperti kode anda sebelumnya)

$data = new AsetController($conn);
$data->index();