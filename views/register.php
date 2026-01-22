<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController($conn);
$auth->checkGuest();
$error = '';

if (isset($_POST['register'])) {
    $error = $auth->register($_POST);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register - PT SATONA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Register</h3>
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="login.php">Login disini</a></small>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cari semua elemen dengan class 'alert'
        var alerts = document.querySelectorAll('.alert');

        alerts.forEach(function(alert) {
            // Set waktu 2000 milidetik (2 detik)
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 2000);
        });
    });
    </script>
</body>

</html>