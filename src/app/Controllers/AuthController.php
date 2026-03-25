<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;

class AuthController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    // GET /register
    public function registerForm(): void
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // POST /register
    public function register(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        if (empty($username)) $errors[] = "Username requis.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
        if (strlen($password) < 6) $errors[] = "Mot de passe trop court (min 6).";
        if ($password !== $confirm) $errors[] = "Mots de passe différents.";
        if ($this->user->findByEmail($email)) $errors[] = "Email déjà utilisé.";
        if ($this->user->findByUsername($username)) $errors[] = "Username déjà pris.";

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('old', $_POST);
            header('Location: /register');
            exit;
        }

        $this->user->create($username, $email, $password);
        Session::set('success', "Inscription réussie ! Connectez-vous.");
        header('Location: /login');
        exit;
    }

    // GET /login
    public function loginForm(): void
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // POST /login
    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::set('errors', ["Email ou mot de passe incorrect."]);
            header('Location: /login');
            exit;
        }

        Session::set('user_id', $user['id']);
        Session::set('username', $user['username']);
        header('Location: /');
        exit;
    }

    // GET /logout
    public function logout(): void
    {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}