<?php
include 'config/database.php';

// --- LOGIKA CRUD (CREATE, UPDATE, DELETE) ---

// 1. Logika Tambah Data (Simpan)
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_aset'];
    $tgl = $_POST['tanggal_perolehan'];
    $harga = $_POST['harga_perolehan'];

    if (!empty($nama) && !empty($tgl) && !empty($harga)) {
        mysqli_query($conn, "INSERT INTO satona VALUES (NULL, '$nama', '$tgl', '$harga')");
        // Redirect agar tidak resubmit saat refresh
        header("Location: index.php");
        exit;
    }
}

// 2. Logika Edit Data (Update)
if (isset($_POST['update'])) {
    $id = $_POST['kode_aset'];
    $nama = $_POST['nama_aset'];
    $tgl = $_POST['tanggal_perolehan'];
    $harga = $_POST['harga_perolehan'];

    mysqli_query($conn, "UPDATE satona SET 
        nama_aset='$nama', 
        tanggal_perolehan='$tgl', 
        harga_perolehan='$harga' 
        WHERE kode_aset='$id'");

    header("Location: index.php");
    exit;
}

// 3. Logika Hapus Data
if (isset($_POST['hapus_data'])) {
    $id = $_POST['kode_aset'];
    mysqli_query($conn, "DELETE FROM satona WHERE kode_aset='$id'");

    header("Location: index.php");
    exit;
}

// --- LOGIKA PAGINATION & PENCARIAN (Sama seperti sebelumnya) ---

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query Data
$query = "SELECT * FROM satona 
          WHERE nama_aset LIKE '%$search%' 
          ORDER BY kode_aset DESC 
          LIMIT $start, $limit";
$data = mysqli_query($conn, $query);

// Query Total Data
$totalData = mysqli_query($conn, "SELECT * FROM satona WHERE nama_aset LIKE '%$search%'");
$total = mysqli_num_rows($totalData);
$totalPage = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventaris Aset PT SATONA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

    <h3>Inventaris Aset PT SATONA</h3>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
        + Tambah Aset
    </button>

    <form class="mb-3" method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama aset..."
                value="<?= $search ?>">
            <button class="btn btn-secondary">Cari</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Tanggal Perolehan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $row['kode_aset']; ?></td>
                <td><?= $row['nama_aset']; ?></td>
                <td><?= $row['tanggal_perolehan']; ?></td>
                <td>Rp <?= number_format($row['harga_perolehan']); ?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editModal<?= $row['kode_aset']; ?>">
                        Edit
                    </button>

                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#hapusModal<?= $row['kode_aset']; ?>">
                        Hapus
                    </button>
                </td>
            </tr>

            <div class="modal fade" id="editModal<?= $row['kode_aset']; ?>" tabindex="-1">
                <div class="modal-dialog">
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
                                    <label>Tanggal Perolehan</label>
                                    <input type="date" name="tanggal_perolehan" class="form-control"
                                        value="<?= $row['tanggal_perolehan']; ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label>Harga Perolehan</label>
                                    <input type="number" name="harga_perolehan" class="form-control"
                                        value="<?= $row['harga_perolehan']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="hapusModal<?= $row['kode_aset']; ?>" tabindex="-1">
                <div class="modal-dialog">
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="hapus_data" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php } ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
            </li>
            <?php } ?>
        </ul>
    </nav>

    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aset Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Tanggal Perolehan</label>
                            <input type="date" name="tanggal_perolehan" class="form-control" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>