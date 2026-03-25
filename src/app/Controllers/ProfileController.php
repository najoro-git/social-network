<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\User;

class ProfileController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    // GET /profile
    public function index(): void
    {
        Auth::require();
        $user = $this->user->findById(Auth::user()['id']);
        require_once __DIR__ . '/../Views/profile/index.php';
    }

    // GET /profile/edit
    public function editForm(): void
    {
        Auth::require();
        $user = $this->user->findById(Auth::user()['id']);
        require_once __DIR__ . '/../Views/profile/edit.php';
    }

    // POST /profile/edit
    public function edit(): void
    {
        Auth::require();

        $id       = Auth::user()['id'];
        $username = trim($_POST['username'] ?? '');
        $bio      = trim($_POST['bio'] ?? '');
        $errors   = [];

        // Validation
        if (empty($username)) $errors[] = "Username requis.";

        // Vérifie si username déjà pris par un autre user
        $existing = $this->user->findByUsername($username);
        if ($existing && $existing['id'] != $id) {
            $errors[] = "Username déjà pris.";
        }

        // Upload avatar
        $avatarPath = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $mime    = mime_content_type($_FILES['avatar']['tmp_name']);

            if (!in_array($mime, $allowed)) {
                $errors[] = "Format image non supporté (jpg, png, gif, webp).";
            } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Image trop lourde (max 2MB).";
            } else {
                $ext        = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $filename   = 'avatar_' . $id . '_' . time() . '.' . $ext;
                $uploadDir  = __DIR__ . '/../../public/uploads/avatars/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename);
                $avatarPath = '/uploads/avatars/' . $filename;
            }
        }

        if (!empty($errors)) {
            \App\Core\Session::set('errors', $errors);
            header('Location: /profile/edit');
            exit;
        }

        $this->user->update($id, $username, $bio, $avatarPath);
        \App\Core\Session::set('username', $username);
        \App\Core\Session::set('success', "Profil mis à jour !");
        header('Location: /profile');
        exit;
    }
}