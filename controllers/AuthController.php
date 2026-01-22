<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController
{
    private $model;

    public function __construct($koneksi)
    {
        $this->model = new AuthModel($koneksi);
    }
  
    public function register($data)
    {
        $username = htmlspecialchars($data['username']);
        $email = htmlspecialchars($data['email']);
        $name = htmlspecialchars($data['name']);
        $password = $data['password'];

        $result = $this->model->registerUser($username, $email, $name, $password);

        if ($result === true) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Registrasi berhasil! Silakan login.'];
            header("Location: login.php");
            exit;
        } else {
            return $result;
        }
    }

    public function login($loginInput, $password)
    {
        $user = $this->model->findUserByLogin($loginInput);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_login'] = true;

            header("Location: ../index.php");
            exit;
        } else {
            return "Username/Email atau Password salah!";
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: views/login.php");
        exit;
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
            header("Location: views/login.php");
            exit;
        }
    }

    public function checkGuest()
    {
        if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
            header("Location: ../index.php");
            exit;
        }
    }
}
?>