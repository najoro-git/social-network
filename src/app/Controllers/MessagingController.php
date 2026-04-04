<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MessagingController
{
    private Conversation $conversation;
    private Message      $message;
    private User         $user;

    public function __construct()
    {
        $this->conversation = new Conversation();
        $this->message      = new Message();
        $this->user         = new User();
    }

    // GET /messages — Liste des conversations
    public function index(): void
    {
        Auth::require();
        $userId        = Auth::user()['id'];
        $conversations = $this->conversation->getByUser($userId);
        require_once __DIR__ . '/../Views/messaging/index.php';
    }

    // GET /messages/{id} — Ouvrir une conversation
    public function show(): void
    {
        Auth::require();

        $convId = (int) ($_GET['id'] ?? 0);
        $userId = Auth::user()['id'];
        $conv   = $this->conversation->findById($convId);

        // Vérifier que l'user fait partie de la conversation
        if (!$conv || ($conv['user1_id'] != $userId && $conv['user2_id'] != $userId)) {
            header('Location: /messages');
            exit;
        }

        // Récupérer l'autre user
        $otherId   = $conv['user1_id'] == $userId ? $conv['user2_id'] : $conv['user1_id'];
        $otherUser = $this->user->findById($otherId);

        // Pagination
        $perPage     = 50;
        $total       = $this->message->countByConversation($convId);
        $totalPages  = (int) ceil($total / $perPage);
        $currentPage = max(1, (int) ($_GET['page'] ?? $totalPages));
        $offset      = ($currentPage - 1) * $perPage;

        // Récupérer les messages
        $messages = $this->message->getByConversation($convId, $perPage, $offset);

        // Marquer comme lus
        $this->message->markAsRead($convId, $userId);

        require_once __DIR__ . '/../Views/messaging/show.php';
    }

    // GET /messages/new?user_id= — Nouvelle conversation
    public function new(): void
    {
        Auth::require();

        $userId      = Auth::user()['id'];
        $otherUserId = (int) ($_GET['user_id'] ?? 0);

        if (!$otherUserId || $otherUserId === $userId) {
            header('Location: /messages');
            exit;
        }

        $otherUser = $this->user->findById($otherUserId);
        if (!$otherUser) {
            header('Location: /messages');
            exit;
        }

        // Trouver ou créer la conversation
        $convId = $this->conversation->findOrCreate($userId, $otherUserId);
        header('Location: /messages/' . $convId);
        exit;
    }

    // POST /messages/send — Envoyer un message
    public function send(): void
    {
        Auth::require();

        $convId  = (int) ($_POST['conv_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $userId  = Auth::user()['id'];
        $errors  = [];

        // Validation
        if (empty($content)) $errors[] = "Le message est requis.";
        if (strlen($content) > 1000) $errors[] = "Maximum 1000 caractères.";

        // Vérifier accès
        $conv = $this->conversation->findById($convId);
        if (!$conv || ($conv['user1_id'] != $userId && $conv['user2_id'] != $userId)) {
            header('Location: /messages');
            exit;
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            header('Location: /messages/' . $convId);
            exit;
        }

        $this->message->send($convId, $userId, $content);
        header('Location: /messages/' . $convId);
        exit;
    }

    // POST /messages/delete — Supprimer un message
    public function delete(): void
    {
        Auth::require();

        $msgId  = (int) ($_POST['message_id'] ?? 0);
        $convId = (int) ($_POST['conv_id'] ?? 0);
        $userId = Auth::user()['id'];

        $this->message->delete($msgId, $userId);
        header('Location: /messages/' . $convId);
        exit;
    }

    // GET /messages/search — Chercher un user pour démarrer une conv
    public function search(): void
    {
        Auth::require();

        $query  = trim($_GET['q'] ?? '');
        $userId = Auth::user()['id'];
        $users  = [];

        if (strlen($query) >= 2) {
            $users = $this->user->search($query, $userId);
        }

        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }
}