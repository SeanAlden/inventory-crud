<?php
class AsetModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function insert($nama, $tgl, $harga)
    {
        $query = "INSERT INTO satona VALUES (NULL, '$nama', '$tgl', '$harga')";
        return mysqli_query($this->conn, $query);
    }

    public function update($id, $nama, $tgl, $harga)
    {
        $query = "UPDATE satona SET 
                  nama_aset='$nama', 
                  tanggal_perolehan='$tgl', 
                  harga_perolehan='$harga' 
                  WHERE kode_aset='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function delete($id)
    {
        $query = "DELETE FROM satona WHERE kode_aset='$id'";
        return mysqli_query($this->conn, $query);
    }

    // public function getAll($search, $start, $limit)
    // {
    //     $query = "SELECT * FROM satona 
    //               WHERE nama_aset LIKE '%$search%' 
    //               ORDER BY kode_aset DESC 
    //               LIMIT $start, $limit";
    //     return mysqli_query($this->conn, $query);
    // }

    public function getAll($search, $start, $limit, $order = 'DESC')
    {
        // Validasi keamanan agar hanya bisa 'ASC' atau 'DESC' (Mencegah SQL Injection)
        $direction = ($order == 'ASC') ? 'ASC' : 'DESC';

        $query = "SELECT * FROM satona 
                  WHERE nama_aset LIKE '%$search%' 
                  ORDER BY kode_aset $direction 
                  LIMIT $start, $limit";
        return mysqli_query($this->conn, $query);
    }

    public function countTotal($search)
    {
        $query = "SELECT * FROM satona WHERE nama_aset LIKE '%$search%'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result);
    }
}
?>