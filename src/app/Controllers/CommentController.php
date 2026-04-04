<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Models\Comment;
use App\Models\Post;

class CommentController
{
    private Comment $comment;
    private Post    $post;

    public function __construct()
    {
        $this->comment = new Comment();
        $this->post    = new Post();
    }

    // POST /comments/create
    public function create(): void
    {
        Auth::require();

        $postId  = (int) ($_POST['post_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $userId  = Auth::user()['id'];
        $errors  = [];

        // Validation
        if (empty($content)) $errors[] = "Le commentaire est requis.";
        if (strlen($content) > 500) $errors[] = "Maximum 500 caractères.";

        // Vérifie que le post existe
        $post = $this->post->findById($postId);
        if (!$post) $errors[] = "Publication introuvable.";

        if (!empty($errors)) {
            Session::set('errors', $errors);
            header('Location: /posts/' . $postId);
            exit;
        }

        $this->comment->create($userId, $postId, $content);
        header('Location: /posts/' . $postId);
        exit;
    }

    // POST /comments/delete
    public function delete(): void
    {
        Auth::require();

        $id     = (int) ($_POST['id'] ?? 0);
        $postId = (int) ($_POST['post_id'] ?? 0);
        $userId = Auth::user()['id'];

        $comment = $this->comment->findById($id);
        if ($comment && $comment['user_id'] == $userId) {
            $this->comment->delete($id, $userId);
        }

        header('Location: /posts/' . $postId);
        exit;
    }
}