<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Créer une publication
    public function create(int $userId, string $content, ?string $image): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, content, image)
            VALUES (:user_id, :content, :image)
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'content' => $content,
            'image'   => $image,
        ]);
    }

    // Récupérer un post par ID
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.avatar
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Feed principal (tous les posts)
    public function getFeed(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.avatar
            FROM posts p
            JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Posts d'un utilisateur (pour le profil)
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.avatar
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    // Modifier une publication
    public function update(int $id, int $userId, string $content): bool
    {
        $stmt = $this->db->prepare("
            UPDATE posts
            SET content = :content, is_edited = 1
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([
            'content' => $content,
            'id'      => $id,
            'user_id' => $userId,
        ]);
    }

    // Supprimer une publication
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM posts
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([
            'id'      => $id,
            'user_id' => $userId,
        ]);
    }

    // Compter tous les posts (pour pagination)
    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM posts");
        return (int) $stmt->fetchColumn();
    }
}