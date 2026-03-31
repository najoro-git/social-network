<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Models\Post;
use function imagecreatefromjpeg;
use function imagecreatefrompng;
use function imagejpeg;
use function imagepng;
use function imagedestroy;
use function copy;

class PostController
{
    private Post $post;

    public function __construct()
    {
        $this->post = new Post();
    }

    // POST /posts/create
    public function create(): void
    {
        Auth::require();

        $content = trim($_POST['content'] ?? '');
        $userId  = Auth::user()['id'];
        $errors  = [];

        // Validation texte
        if (empty($content)) $errors[] = "Le contenu est requis.";
        if (strlen($content) > 500) $errors[] = "Maximum 500 caractères.";

        // Upload image
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            $mime    = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($mime, $allowed)) {
                $errors[] = "Format image non supporté (jpg, png, gif).";
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $errors[] = "Image trop lourde (max 5MB).";
            } else {
                $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename  = 'post_' . $userId . '_' . time() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/posts/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Compression image
                $this->compressImage(
                    $_FILES['image']['tmp_name'],
                    $uploadDir . $filename,
                    $mime
                );

                $imagePath = '/uploads/posts/' . $filename;
            }
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            header('Location: /');
            exit;
        }

        $this->post->create($userId, $content, $imagePath);
        header('Location: /');
        exit;
    }

    // GET /posts/edit?id=
    public function editForm(): void
    {
        Auth::require();

        $id   = (int) ($_GET['id'] ?? 0);
        $post = $this->post->findById($id);

        if (!$post || $post['user_id'] != Auth::user()['id']) {
            header('Location: /');
            exit;
        }

        require_once __DIR__ . '/../Views/posts/edit.php';
    }

    // POST /posts/edit
    public function edit(): void
    {
        Auth::require();

        $id      = (int) ($_POST['id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $userId  = Auth::user()['id'];
        $errors  = [];

        if (empty($content)) $errors[] = "Le contenu est requis.";
        if (strlen($content) > 500) $errors[] = "Maximum 500 caractères.";

        if (!empty($errors)) {
            Session::set('errors', $errors);
            header('Location: /posts/edit?id=' . $id);
            exit;
        }

        $this->post->update($id, $userId, $content);
        Session::set('success', "Publication modifiée !");
        header('Location: /');
        exit;
    }

    // POST /posts/delete
    public function delete(): void
    {
        Auth::require();

        $id     = (int) ($_POST['id'] ?? 0);
        $userId = Auth::user()['id'];
        $post   = $this->post->findById($id);

        if ($post && $post['user_id'] == $userId) {
            // Supprime l'image si elle existe
            if ($post['image']) {
                $filePath = __DIR__ . '/../../public' . $post['image'];
                if (file_exists($filePath)) unlink($filePath);
            }
            $this->post->delete($id, $userId);
        }

        header('Location: /');
        exit;
    }

    // Compression image (US-010)
    private function compressImage(string $source, string $dest, string $mime): void
    {
        switch ($mime) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($source);
                imagejpeg($img, $dest, 75);
                break;
            case 'image/png':
                $img = imagecreatefrompng($source);
                imagepng($img, $dest, 7);
                break;
            case 'image/gif':
                copy($source, $dest);
                break;
        }
        if (isset($img)) imagedestroy($img);
    }
}