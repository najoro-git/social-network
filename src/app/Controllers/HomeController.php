<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Post;

class HomeController
{
    private Post $post;

    public function __construct()
    {
        $this->post = new Post();
    }

    public function index(): void
    {
        // Pagination
        $perPage     = 20;
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;
        $total       = $this->post->countAll();
        $totalPages  = (int) ceil($total / $perPage);

        $posts = $this->post->getFeed($perPage, $offset);

        require_once __DIR__ . '/../Views/home/index.php';
    }
}