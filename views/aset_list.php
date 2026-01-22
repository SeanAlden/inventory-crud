<!DOCTYPE html>
<html>

<head>
    <title>Inventaris Aset PT SATONA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .sidebar {
        min-height: 100vh;
        background-color: #212529;
        color: white;
    }

    .sidebar a {
        color: #adb5bd;
        text-decoration: none;
        padding: 10px 15px;
        display: block;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #0d6efd;
        color: white;
        border-radius: 5px;
    }

    /* Style tambahan untuk link sorting di header tabel */
    .sort-link {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sort-link:hover {
        color: #d1e7dd;
    }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 250px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-box-seam fs-4 me-2"></i> <span class="fs-4">PT SATONA</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active" aria-current="page">
                        <i class="bi bi-table me-2"></i> Inventory
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <strong><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; height: 100vh; overflow-y: auto;">

            <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
                <div id="liveToast"
                    class="alert alert-<?= $_SESSION['flash_message']['type']; ?> alert-dismissible fade show shadow"
                    role="alert">
                    <strong>Notifikasi:</strong> <?= $_SESSION['flash_message']['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>

            <h3 class="mb-4">Inventaris</h3>

            <div class="card shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">
                            + Tambah Aset
                        </button>

                        <form method="GET" class="d-flex align-items-center">
                            <input type="hidden" name="search" value="<?= $search ?>">
                            <input type="hidden" name="page" value="1">
                            <input type="hidden" name="order" value="<?= $order ?>">

                            <span class="me-2 ms-3">Show:</span>
                            <select name="limit" class="form-select form-select-sm" style="width: 70px;"
                                onchange="this.form.submit()">
                                <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                                <option value="20" <?= ($limit == 20) ? 'selected' : '' ?>>20</option>
                                <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                            </select>
                        </form>
                    </div>

                    <form method="GET" style="width: 300px;">
                        <input type="hidden" name="limit" value="<?= $limit ?>">
                        <input type="hidden" name="order" value="<?= $order ?>">

                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari nama aset..." value="<?= $search ?>">
                            <button class="btn btn-secondary btn-sm">Cari</button>
                        </div>
                    </form>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 100px;">
                                <?php 
                                    // Tentukan urutan selanjutnya (kebalikan dari saat ini)
                                    $nextOrder = ($order == 'ASC') ? 'DESC' : 'ASC';
                                    // Tentukan icon (Jika ASC pakai panah atas, jika DESC panah bawah)
                                    $iconClass = ($order == 'ASC') ? 'bi-sort-numeric-down-alt' : 'bi-sort-numeric-down';
                                ?>
                                <a href="?order=<?= $nextOrder ?>&search=<?= $search ?>&limit=<?= $limit ?>&page=<?= $page ?>"
                                    class="sort-link">
                                    Kode <i class="bi <?= $iconClass ?>"></i>
                                </a>
                            </th>
                            <th>Nama Aset</th>
                            <th>Tanggal Perolehan</th>
                            <th>Harga</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($data) && mysqli_num_rows($data) > 0) {
                            while ($row = mysqli_fetch_assoc($data)) {
                        ?>
                        <tr>
                            <td><?= $row['kode_aset']; ?></td>
                            <td><?= $row['nama_aset']; ?></td>
                            <td><?= $row['tanggal_perolehan']; ?></td>
                            <td>Rp <?= number_format($row['harga_perolehan']); ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['kode_aset']; ?>">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#hapusModal<?= $row['kode_aset']; ?>">Hapus</button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['kode_aset']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Aset</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="kode_aset" value="<?= $row['kode_aset']; ?>">
                                            <div class="mb-2">
                                                <label>Nama Aset</label>
                                                <input type="text" name="nama_aset" class="form-control"
                                                    value="<?= $row['nama_aset']; ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Tanggal & Jam Perolehan</label>
                                                <input type="datetime-local" name="tanggal_perolehan"
                                                    class="form-control"
                                                    value="<?= date('Y-m-d\TH:i', strtotime($row['tanggal_perolehan'])); ?>"
                                                    required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Harga Perolehan</label>
                                                <input type="number" name="harga_perolehan" class="form-control"
                                                    value="<?= $row['harga_perolehan']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="hapusModal<?= $row['kode_aset']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="kode_aset" value="<?= $row['kode_aset']; ?>">
                                            <p>Apakah Anda yakin ingin menghapus data aset:
                                                <strong><?= $row['nama_aset']; ?></strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="hapus_data"
                                                class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Data tidak ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing <?= $showingStart ?> to <?= $showingEnd ?> of <?= $total ?> items
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?= $page - 1 ?>&search=<?= $search ?>&limit=<?= $limit ?>&order=<?= $order ?>">Previous</a>
                            </li>

                            <?php
                            $pageNumbers = [];
                            if ($totalPage <= 7) {
                                for ($i = 1; $i <= $totalPage; $i++) $pageNumbers[] = $i;
                            } else {
                                if ($page <= 4) {
                                    for ($i = 1; $i <= 5; $i++) $pageNumbers[] = $i;
                                    $pageNumbers[] = '...';
                                    $pageNumbers[] = $totalPage;
                                } elseif ($page >= $totalPage - 3) {
                                    $pageNumbers[] = 1;
                                    $pageNumbers[] = '...';
                                    for ($i = $totalPage - 4; $i <= $totalPage; $i++) $pageNumbers[] = $i;
                                } else {
                                    $pageNumbers[] = 1;
                                    $pageNumbers[] = '...';
                                    $pageNumbers[] = $page - 1;
                                    $pageNumbers[] = $page;
                                    $pageNumbers[] = $page + 1;
                                    $pageNumbers[] = '...';
                                    $pageNumbers[] = $totalPage;
                                }
                            }
                            
                            foreach ($pageNumbers as $p) {
                                if ($p == '...') {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                } else {
                                    $active = ($page == $p) ? 'active' : '';
                                    // Tambahkan order pada setiap link halaman
                            echo '<li class="page-item ' . $active . '"><a class="page-link"
                                    href="?page=' . $p . '&search=' . $search . '&limit=' . $limit . '&order=' . $order . '">'
                                    . $p . '</a></li>';
                            }
                            }
                            ?>

                            <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?= $page + 1 ?>&search=<?= $search ?>&limit=<?= $limit ?>&order=<?= $order ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aset Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Tanggal & Jam Perolehan</label>
                            <input type="datetime-local" name="tanggal_perolehan" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Harga Perolehan</label>
                            <input type="number" name="harga_perolehan" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var alertBox = document.getElementById('liveToast');
        if (alertBox) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alertBox);
                bsAlert.close();
            }, 2000);
        }
    });
    </script>
</body>

</html>