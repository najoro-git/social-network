<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Like
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Ajouter un like
    public function add(int $userId, int $postId): bool
    {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO likes (user_id, post_id)
            VALUES (:user_id, :post_id)
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);
    }

    // Retirer un like
    public function remove(int $userId, int $postId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM likes
            WHERE user_id = :user_id AND post_id = :post_id
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);
    }

    // Vérifier si un user a liké un post
    public function hasLiked(int $userId, int $postId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM likes
            WHERE user_id = :user_id AND post_id = :post_id
        ");
        $stmt->execute([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);
        return (bool) $stmt->fetchColumn();
    }

    // Compter les likes d'un post
    public function count(int $postId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM likes WHERE post_id = :post_id
        ");
        $stmt->execute(['post_id' => $postId]);
        return (int) $stmt->fetchColumn();
    }

    // Récupérer les likes pour une liste de posts
    public function getLikesForPosts(array $postIds, int $userId): array
    {
        if (empty($postIds)) return [];

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));

        $stmt = $this->db->prepare("
            SELECT post_id, COUNT(*) as total,
                   MAX(CASE WHEN user_id = ? THEN 1 ELSE 0 END) as user_liked
            FROM likes
            WHERE post_id IN ($placeholders)
            GROUP BY post_id
        ");

        $stmt->execute(array_merge([$userId], $postIds));
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['post_id']] = [
                'total'      => (int) $row['total'],
                'user_liked' => (bool) $row['user_liked'],
            ];
        }
        return $result;
    }
}