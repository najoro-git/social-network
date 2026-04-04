<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Like;
use App\Models\Post;

class LikeController
{
    private Like $like;
    private Post $post;

    public function __construct()
    {
        $this->like = new Like();
        $this->post = new Post();
    }

    // POST /posts/like (AJAX)
    public function toggle(): void
    {
        Auth::require();

        header('Content-Type: application/json');

        $postId = (int) ($_POST['post_id'] ?? 0);
        $userId = Auth::user()['id'];

        // Vérifie que le post existe
        $post = $this->post->findById($postId);
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'Post introuvable.']);
            exit;
        }

        // Toggle like
        if ($this->like->hasLiked($userId, $postId)) {
            $this->like->remove($userId, $postId);
            $liked = false;
        } else {
            $this->like->add($userId, $postId);
            $liked = true;
        }

        $total = $this->like->count($postId);

        echo json_encode([
            'success' => true,
            'liked'   => $liked,
            'total'   => $total,
        ]);
        exit;
    }
}