<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Conversation
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Trouver ou créer une conversation entre 2 users
    public function findOrCreate(int $userId1, int $userId2): int
    {
        // Toujours stocker avec le plus petit ID en premier
        $u1 = min($userId1, $userId2);
        $u2 = max($userId1, $userId2);

        $stmt = $this->db->prepare("
            SELECT id FROM conversations
            WHERE user1_id = :u1 AND user2_id = :u2
        ");
        $stmt->execute(['u1' => $u1, 'u2' => $u2]);
        $conv = $stmt->fetch();

        if ($conv) return (int) $conv['id'];

        $stmt = $this->db->prepare("
            INSERT INTO conversations (user1_id, user2_id)
            VALUES (:u1, :u2)
        ");
        $stmt->execute(['u1' => $u1, 'u2' => $u2]);
        return (int) $this->db->lastInsertId();
    }

    // Récupérer toutes les conversations d'un user
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                -- Infos de l'autre user
                u.username AS other_username,
                u.avatar   AS other_avatar,
                u.id       AS other_id,
                -- Dernier message
                m.content    AS last_message,
                m.created_at AS last_message_at,
                m.sender_id  AS last_sender_id,
                -- Nombre de messages non lus
                (SELECT COUNT(*) FROM messages
                 WHERE conversation_id = c.id
                 AND sender_id != :uid
                 AND is_read = 0) AS unread_count
            FROM conversations c
            JOIN users u ON u.id = IF(c.user1_id = :uid2, c.user2_id, c.user1_id)
            LEFT JOIN messages m ON m.id = (
                SELECT id FROM messages
                WHERE conversation_id = c.id
                ORDER BY created_at DESC LIMIT 1
            )
            WHERE c.user1_id = :uid3 OR c.user2_id = :uid4
            ORDER BY COALESCE(m.created_at, c.created_at) DESC
        ");
        $stmt->execute([
            'uid'  => $userId,
            'uid2' => $userId,
            'uid3' => $userId,
            'uid4' => $userId,
        ]);
        return $stmt->fetchAll();
    }

    // Trouver une conversation par ID
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT * FROM conversations WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Compter total non lus pour un user
    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            WHERE (c.user1_id = :uid OR c.user2_id = :uid2)
            AND m.sender_id != :uid3
            AND m.is_read = 0
        ");
        $stmt->execute([
            'uid'  => $userId,
            'uid2' => $userId,
            'uid3' => $userId,
        ]);
        return (int) $stmt->fetchColumn();
    }
}