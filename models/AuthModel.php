<?php

require_once __DIR__ . '/../config/database.php';

class AuthModel
{
    private $db;

    public function __construct($koneksi)
    {
        $this->db = $koneksi;
    }
    public function registerUser($username, $email, $name, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, name, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $username, $email, $name, $hashedPassword);

        try {
            if ($stmt->execute()) {
                return true; 
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $errorMessage = $e->getMessage();

                if (strpos($errorMessage, 'username') !== false) {
                    return "Username '$username' sudah digunakan! Silakan pilih yang lain.";
                }
                if (strpos($errorMessage, 'email') !== false) {
                    return "Email '$email' sudah terdaftar! Silakan login.";
                }
                return "Username atau Email sudah terdaftar.";
            }
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }

        return "Gagal mendaftarkan pengguna.";
    }

    public function findUserByLogin($loginInput)
    {
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>