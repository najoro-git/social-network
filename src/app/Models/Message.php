<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Message
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Envoyer un message
    public function send(int $convId, int $senderId, string $content): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO messages (conversation_id, sender_id, content)
            VALUES (:conv_id, :sender_id, :content)
        ");
        $result = $stmt->execute([
            'conv_id'   => $convId,
            'sender_id' => $senderId,
            'content'   => $content,
        ]);

        // Mettre à jour updated_at de la conversation
        if ($result) {
            $this->db->prepare("
                UPDATE conversations SET updated_at = NOW()
                WHERE id = :id
            ")->execute(['id' => $convId]);
        }

        return $result;
    }

    // Récupérer les messages d'une conversation
    public function getByConversation(int $convId, int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username, u.avatar
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = :conv_id
            ORDER BY m.created_at ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':conv_id', $convId, PDO::PARAM_INT);
        $stmt->bindValue(':limit',   $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Marquer les messages comme lus
    public function markAsRead(int $convId, int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE messages
            SET is_read = 1
            WHERE conversation_id = :conv_id
            AND sender_id != :user_id
            AND is_read = 0
        ");
        $stmt->execute([
            'conv_id' => $convId,
            'user_id' => $userId,
        ]);
    }

    // Compter les messages d'une conversation
    public function countByConversation(int $convId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM messages WHERE conversation_id = :conv_id
        ");
        $stmt->execute(['conv_id' => $convId]);
        return (int) $stmt->fetchColumn();
    }

    // Supprimer un message
    public function delete(int $id, int $senderId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM messages
            WHERE id = :id AND sender_id = :sender_id
        ");
        return $stmt->execute([
            'id'        => $id,
            'sender_id' => $senderId,
        ]);
    }
}