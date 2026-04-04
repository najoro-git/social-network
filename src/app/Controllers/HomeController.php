<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class HomeController
{
    private Post    $post;
    private Like    $like;
    private Comment $comment;

    public function root(): void
    {
        if (\App\Core\Auth::check()) {
            // Connecté → Feed
            $this->index();
        } else {
            // Non connecté → Landing page
            require_once __DIR__ . '/../Views/landing.php';
        }
    }

    public function landing(): void
    {
        require_once __DIR__ . '/../Views/landing.php';
    }

    public function __construct()
    {
        $this->post    = new Post();
        $this->like    = new Like();
        $this->comment = new Comment();
    }

    public function index(): void
    {
        $perPage     = 20;
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;
        $total       = $this->post->countAll();
        $totalPages  = (int) ceil($total / $perPage);

        $posts = $this->post->getFeed($perPage, $offset);

        $likesData    = [];
        $commentsData = [];

        if (!empty($posts)) {
            $postIds = array_column($posts, 'id');

            if (Auth::check()) {
                $userId    = Auth::user()['id'];
                $likesData = $this->like->getLikesForPosts($postIds, $userId);
            }

            $commentsData = $this->comment->getCountsForPosts($postIds);

        }

        require_once __DIR__ . '/../Views/home/index.php';

    }

}