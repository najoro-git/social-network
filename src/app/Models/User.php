<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User //your own custom data type to represent a user in your application
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $username, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ");
        return $stmt->execute([
            'username' => $username,
            'email'    => $email,
            'password' => $hash,
        ]);
    }

    public function update(int $id, string $username, string $bio, ?string $avatarPath): bool
    {
        if ($avatarPath) {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET username = :username, bio = :bio, avatar = :avatar
                WHERE id = :id
            ");
            return $stmt->execute([
                'username' => $username,
                'bio'      => $bio,
                'avatar'   => $avatarPath,
                'id'       => $id,
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET username = :username, bio = :bio
                WHERE id = :id
            ");
            return $stmt->execute([
                'username' => $username,
                'bio'      => $bio,
                'id'       => $id,
            ]);
        }
    }
}