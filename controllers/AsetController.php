<?php
include __DIR__ . '/../models/AsetModel.php';

class AsetController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new AsetModel($conn);
    }

    public function index()
    {
        // CRUD

        if (isset($_POST['simpan'])) {
            $nama = $_POST['nama_aset'];
            $tgl = $_POST['tanggal_perolehan'];
            $harga = $_POST['harga_perolehan'];

            if (!empty($nama) && !empty($tgl) && !empty($harga)) {
                // Cek hasil return dari model (True/False)
                if ($this->model->insert($nama, $tgl, $harga)) {
                    $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Data berhasil ditambahkan!'];
                } else {
                    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan data!'];
                }
                header("Location: index.php");
                exit;
            }
        }

        if (isset($_POST['update'])) {
            $id = $_POST['kode_aset'];
            $nama = $_POST['nama_aset'];
            $tgl = $_POST['tanggal_perolehan'];
            $harga = $_POST['harga_perolehan'];

            if ($this->model->update($id, $nama, $tgl, $harga)) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Data berhasil diperbarui!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal memperbarui data!'];
            }
            header("Location: index.php");
            exit;
        }

        if (isset($_POST['hapus_data'])) {
            $id = $_POST['kode_aset'];

            if ($this->model->delete($id)) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Data berhasil dihapus!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data!'];
            }
            header("Location: index.php");
            exit;
        }

        // Logika untuk Pagination dan Pencarian

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $start = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // 2. Tambahan Logika Order (Sorting)
        // Jika ada parameter 'order' di URL dan nilainya 'ASC', maka set 'ASC', selain itu default 'DESC'
        $order = (isset($_GET['order']) && strtoupper($_GET['order']) == 'ASC') ? 'ASC' : 'DESC';

        // 3. Panggil Model dengan parameter order
        $data = $this->model->getAll($search, $start, $limit, $order);
        $total = $this->model->countTotal($search);

        // // Ambil Data dari Model
        // $data = $this->model->getAll($search, $start, $limit);
        // $total = $this->model->countTotal($search);

        // Hitung Logic Pagination Dasar
        $totalPage = ceil($total / $limit);
        $showingStart = ($total > 0) ? $start + 1 : 0;
        $showingEnd = ($total > 0) ? min($start + $limit, $total) : 0;

        include 'views/aset_list.php';
    }
}
?>