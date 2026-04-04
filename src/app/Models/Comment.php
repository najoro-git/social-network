<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Comment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Ajouter un commentaire
    public function create(int $userId, int $postId, string $content): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (user_id, post_id, content)
            VALUES (:user_id, :post_id, :content)
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'post_id' => $postId,
            'content' => $content,
        ]);
    }

    // Récupérer les commentaires d'un post
    public function getByPost(int $postId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.avatar
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
            ORDER BY c.created_at ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit',   $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Compter les commentaires d'un post
    public function countByPost(int $postId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM comments WHERE post_id = :post_id
        ");
        $stmt->execute(['post_id' => $postId]);
        return (int) $stmt->fetchColumn();
    }

    // Compter les commentaires pour une liste de posts
    public function getCountsForPosts(array $postIds): array
    {
        if (empty($postIds)) return [];

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $stmt = $this->db->prepare("
            SELECT post_id, COUNT(*) as total
            FROM comments
            WHERE post_id IN ($placeholders)
            GROUP BY post_id
        ");
        $stmt->execute($postIds);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['post_id']] = (int) $row['total'];
        }
        return $result;
    }

    // Trouver un commentaire par ID
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT * FROM comments WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Supprimer un commentaire
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM comments
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([
            'id'      => $id,
            'user_id' => $userId,
        ]);
    }
}