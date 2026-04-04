<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Chat avec <?= htmlspecialchars($otherUser['username']) ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        .chat-layout {
            max-width: 700px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 64px);
        }

        /* Chat header */
        .chat-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .chat-header-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--green-dark));
            border: 2px solid var(--blue);
            box-shadow: 0 0 12px var(--blue-glow);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .chat-header-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .chat-header-avatar .material-icons { font-size: 1.3rem; color: var(--text); }

        .chat-header-info { flex: 1; }

        .chat-header-username {
            font-family: var(--font-title);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: 1px;
        }

        .chat-header-status {
            font-size: 0.78rem;
            color: var(--blue);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.1rem;
        }

        .status-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--blue);
            box-shadow: 0 0 6px var(--blue-glow);
        }

        /* Messages container */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            min-height: 0;
        }

        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        /* Date separator */
        .date-separator {
            text-align: center;
            position: relative;
            margin: 0.8rem 0;
        }

        .date-separator span {
            background: var(--bg-primary);
            padding: 0.2rem 1rem;
            font-family: var(--font-title);
            font-size: 0.68rem;
            color: var(--text-muted);
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }

        .date-separator::before {
            content: '';
            position: absolute;
            top: 50%; left: 0; right: 0;
            height: 1px;
            background: var(--border);
        }

        /* Message bubble */
        .msg-wrap {
            display: flex;
            gap: 0.6rem;
            align-items: flex-end;
            animation: msgIn 0.2s ease;
        }

        @keyframes msgIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .msg-wrap.mine {
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--green-dark));
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .msg-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .msg-avatar .material-icons { font-size: 0.9rem; color: var(--text); }

        .msg-bubble-wrap { max-width: 70%; }

        .msg-bubble {
            padding: 0.65rem 1rem;
            border-radius: 16px;
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
            position: relative;
        }

        /* Theirs */
        .msg-wrap:not(.mine) .msg-bubble {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            border-bottom-left-radius: 4px;
            color: var(--text);
        }

        /* Mine */
        .msg-wrap.mine .msg-bubble {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue));
            border: 1px solid rgba(26,111,255,0.4);
            border-bottom-right-radius: 4px;
            color: white;
            box-shadow: 0 0 15px var(--blue-glow);
        }

        .msg-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
            padding: 0 0.3rem;
        }

        .msg-wrap.mine .msg-meta { justify-content: flex-end; }

        .msg-time {
            font-family: var(--font-title);
            font-size: 0.65rem;
            color: var(--text-muted);
        }

        .msg-delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 0;
            display: flex;
            align-items: center;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .msg-bubble-wrap:hover .msg-delete-btn { opacity: 1; }
        .msg-delete-btn:hover { color: #ff4466; }
        .msg-delete-btn .material-icons { font-size: 0.9rem; }

        /* Pagination */
        .chat-pagination {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.5rem 0;
            flex-shrink: 0;
        }

        /* Input area */
        .chat-input-area {
            padding-top: 0.8rem;
            flex-shrink: 0;
        }

        .chat-input-wrap {
            display: flex;
            gap: 0.8rem;
            align-items: flex-end;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 0.8rem;
            backdrop-filter: blur(10px);
        }

        .chat-input-wrap:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 15px var(--blue-glow);
        }

        .chat-textarea {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: var(--font-body);
            font-size: 1rem;
            resize: none;
            max-height: 120px;
            min-height: 40px;
            line-height: 1.5;
        }

        .chat-textarea::placeholder { color: var(--text-muted); }

        .chat-send-btn {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--blue));
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 12px var(--blue-glow);
            transition: all 0.3s ease;
        }

        .chat-send-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px var(--blue-glow);
        }

        .chat-send-btn .material-icons { color: white; font-size: 1.2rem; }

        /* Errors */
        .chat-errors {
            margin-bottom: 0.8rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-main">

<nav class="navbar">
    <a href="/" class="navbar-brand">
        <span class="material-icons">hub</span>
        SOCIALNET
    </a>
    <div class="navbar-links">
        <a href="/"><span class="material-icons">home</span><span>Feed</span></a>
        <a href="/messages"><span class="material-icons">chat</span><span>Messages</span></a>
        <a href="/profile"><span class="material-icons">account_circle</span><span><?= htmlspecialchars(\App\Core\Session::get('username') ?? '') ?></span></a>
        <a href="/logout" class="btn-logout"><span class="material-icons">power_settings_new</span><span>Quitter</span></a>
    </div>
</nav>

<div class="chat-layout">

    <!-- CHAT HEADER -->
    <div class="chat-header">
        <a href="/messages" style="color:var(--silver);display:flex;align-items:center">
            <span class="material-icons">arrow_back</span>
        </a>

        <div class="chat-header-avatar">
            <?php if ($otherUser['avatar']): ?>
                <img src="<?= htmlspecialchars($otherUser['avatar']) ?>" alt="avatar">
            <?php else: ?>
                <span class="material-icons">person</span>
            <?php endif; ?>
        </div>

        <div class="chat-header-info">
            <div class="chat-header-username">
                <?= htmlspecialchars($otherUser['username']) ?>
            </div>
            <div class="chat-header-status">
                <div class="status-dot"></div>
                En ligne
            </div>
        </div>
    </div>

    <!-- ERRORS -->
    <?php if (\App\Core\Session::get('errors')): ?>
    <div class="chat-errors">
        <div class="alert alert-error">
            <span class="material-icons">error</span>
            <ul>
                <?php foreach (\App\Core\Session::get('errors') as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php \App\Core\Session::set('errors', []); ?>
    </div>
    <?php endif; ?>

    <!-- PAGINATION HAUT -->
    <?php if ($totalPages > 1): ?>
    <div class="chat-pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/messages/<?= $conv['id'] ?>?page=<?= $i ?>"
               class="<?= $i === $currentPage ? 'active' : '' ?>"
               style="padding:0.3rem 0.7rem;border-radius:var(--radius);
                      background:var(--bg-card);border:1px solid var(--border);
                      color:var(--silver);font-family:var(--font-title);
                      font-size:0.75rem;text-decoration:none">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <!-- MESSAGES -->
    <div class="chat-messages" id="chatMessages">

        <?php if (empty($messages)): ?>
            <div class="empty-state" style="margin:auto">
                <span class="material-icons">chat_bubble_outline</span>
                <p>Aucun message</p>
                <p style="font-size:0.78rem;margin-top:0.3rem">
                    Envoyez le premier message !
                </p>
            </div>
        <?php else: ?>

            <?php
            $currentDate = null;
            $myId = \App\Core\Session::get('user_id');
            ?>

            <?php foreach ($messages as $msg): ?>
            <?php
                $msgDate = date('d/m/Y', strtotime($msg['created_at']));
                $isMine  = $msg['sender_id'] == $myId;
            ?>

            <!-- Date separator -->
            <?php if ($msgDate !== $currentDate): ?>
                <?php $currentDate = $msgDate; ?>
                <div class="date-separator">
                    <span><?= $msgDate === date('d/m/Y') ? "AUJOURD'HUI" : $msgDate ?></span>
                </div>
            <?php endif; ?>

            <!-- Message -->
            <div class="msg-wrap <?= $isMine ? 'mine' : '' ?>">

                <?php if (!$isMine): ?>
                <div class="msg-avatar">
                    <?php if ($msg['avatar']): ?>
                        <img src="<?= htmlspecialchars($msg['avatar']) ?>" alt="avatar">
                    <?php else: ?>
                        <span class="material-icons">person</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="msg-bubble-wrap">
                    <div class="msg-bubble">
                        <?= htmlspecialchars($msg['content']) ?>
                    </div>
                    <div class="msg-meta">
                        <span class="msg-time">
                            <?= date('H:i', strtotime($msg['created_at'])) ?>
                        </span>
                        <?php if ($isMine): ?>
                        <form method="POST" action="/messages/delete"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer ce message ?')">
                            <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                            <input type="hidden" name="conv_id"    value="<?= $conv['id'] ?>">
                            <button type="submit" class="msg-delete-btn">
                                <span class="material-icons">delete</span>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- INPUT AREA -->
    <div class="chat-input-area">
        <form method="POST" action="/messages/send" id="msgForm">
            <input type="hidden" name="conv_id" value="<?= $conv['id'] ?>">
            <div class="chat-input-wrap">
                <textarea
                    id="msgContent"
                    name="content"
                    class="chat-textarea"
                    placeholder="Écrire un message..."
                    maxlength="1000"
                    rows="1"></textarea>
                <button type="submit" class="chat-send-btn">
                    <span class="material-icons">send</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script src="/assets/js/main.js"></script>
<script>
    // Auto scroll to bottom
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto resize textarea
    const textarea = document.getElementById('msgContent');
    if (textarea) {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        });

        // Submit on Enter (Shift+Enter = new line)
        textarea.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (textarea.value.trim()) {
                    document.getElementById('msgForm').submit();
                }
            }
        });
    }
</script>
</body>
</html>