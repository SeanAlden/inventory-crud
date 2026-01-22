<?php
include 'config/database.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_aset'];
    $tgl = $_POST['tanggal_perolehan'];
    $harga = $_POST['harga_perolehan'];

    if (!empty($nama) && !empty($tgl) && !empty($harga)) {
        mysqli_query($conn, "INSERT INTO satona VALUES (NULL, '$nama', '$tgl', '$harga')");
        header("Location: index.php");
        exit;
    }
}

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

if (isset($_POST['hapus_data'])) {
    $id = $_POST['kode_aset'];
    mysqli_query($conn, "DELETE FROM satona WHERE kode_aset='$id'");

    header("Location: index.php");
    exit;
}

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM satona 
          WHERE nama_aset LIKE '%$search%' 
          ORDER BY kode_aset DESC 
          LIMIT $start, $limit";
$data = mysqli_query($conn, $query);

$queryTotal = mysqli_query($conn, "SELECT * FROM satona WHERE nama_aset LIKE '%$search%'");
$total = mysqli_num_rows($queryTotal);
$totalPage = ceil($total / $limit);

$showingStart = ($total > 0) ? $start + 1 : 0;
$showingEnd = ($total > 0) ? min($start + $limit, $total) : 0;

?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventaris Aset PT SATONA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

    <h3 class="mb-4">Inventaris Aset PT SATONA</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah Aset
            </button>

            <form method="GET" class="d-flex align-items-center">
                <input type="hidden" name="search" value="<?= $search ?>">
                <input type="hidden" name="page" value="1"> <span class="me-2 ms-3">Show:</span>
                <select name="limit" class="form-select form-select-sm" style="width: 70px;"
                    onchange="this.form.submit()">
                    <option value="1" <?= ($limit == 1) ? 'selected' : '' ?>>1</option>
                    <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= ($limit == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                </select>
            </form>
        </div>

        <form method="GET" style="width: 300px;">
            <input type="hidden" name="limit" value="<?= $limit ?>">
            <div class="input-group">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama aset..."
                    value="<?= $search ?>">
                <button class="btn btn-secondary btn-sm">Cari</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Tanggal Perolehan</th>
                <th>Harga</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($data) > 0) {
                while ($row = mysqli_fetch_assoc($data)) {
                    ?>
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
                                <p>Apakah Anda yakin ingin menghapus: <strong><?= $row['nama_aset']; ?></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="hapus_data" class="btn btn-danger">Hapus</button>
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
                        href="?page=<?= $page - 1 ?>&search=<?= $search ?>&limit=<?= $limit ?>">Previous</a>
                </li>

                <?php
               
                $pageNumbers = [];

                if ($totalPage <= 7) {
                   
                    for ($i = 1; $i <= $totalPage; $i++) {
                        $pageNumbers[] = $i;
                    }
                } else {
                   
                    if ($page <= 4) {
                       
                       
                        for ($i = 1; $i <= 5; $i++)
                            $pageNumbers[] = $i;
                        $pageNumbers[] = '...';
                        $pageNumbers[] = $totalPage;

                    } elseif ($page >= $totalPage - 3) {
                       
                       
                        $pageNumbers[] = 1;
                        $pageNumbers[] = '...';
                        for ($i = $totalPage - 4; $i <= $totalPage; $i++)
                            $pageNumbers[] = $i;

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
                        echo '<li class="page-item ' . $active . '">
                                <a class="page-link" href="?page=' . $p . '&search=' . $search . '&limit=' . $limit . '">' . $p . '</a>
                              </li>';
                    }
                }
                ?>

                <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : ''; ?>">
                    <a class="page-link"
                        href="?page=<?= $page + 1 ?>&search=<?= $search ?>&limit=<?= $limit ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

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