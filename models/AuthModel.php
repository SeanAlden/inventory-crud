<?php

require_once __DIR__ . '/../config/database.php';

class AuthModel
{
    private $db;

    public function __construct($koneksi)
    {
        $this->db = $koneksi;
    }

    // public function registerUser($username, $email, $name, $password)
    // {
    //     // Hash password untuk keamanan tinggi
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //     $query = "INSERT INTO users (username, email, name, password) VALUES (?, ?, ?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("ssss", $username, $email, $name, $hashedPassword);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }

    // Ubah return type: bisa TRUE (sukses) atau STRING (pesan error)
    public function registerUser($username, $email, $name, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, name, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $username, $email, $name, $hashedPassword);

        // --- UPDATE: GUNAKAN TRY-CATCH ---
        try {
            if ($stmt->execute()) {
                return true; // Sukses
            }
        } catch (mysqli_sql_exception $e) {
            // Error Code 1062 artinya Duplicate Entry (Data Kembar)
            if ($e->getCode() == 1062) {
                $errorMessage = $e->getMessage();

                // Cek bagian mana yang kembar berdasarkan pesan error database
                if (strpos($errorMessage, 'username') !== false) {
                    return "Username '$username' sudah digunakan! Silakan pilih yang lain.";
                }
                if (strpos($errorMessage, 'email') !== false) {
                    return "Email '$email' sudah terdaftar! Silakan login.";
                }
                return "Username atau Email sudah terdaftar.";
            }
            // Jika error lain, kembalikan pesan umum
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }

        return "Gagal mendaftarkan pengguna.";
    }

    public function findUserByLogin($loginInput)
    {
        // Mencari berdasarkan Username ATAU Email
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>