<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Messages</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        .messages-layout {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .messages-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .messages-title {
            font-family: var(--font-title);
            font-size: 1rem;
            color: var(--blue);
            letter-spacing: 3px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Search box */
        .search-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .search-box-title {
            font-family: var(--font-title);
            font-size: 0.75rem;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .search-input-wrap {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }

        .search-results {
            margin-top: 0.8rem;
            display: none;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .search-result-item:hover {
            background: var(--bg-glass);
            border-color: var(--border);
        }

        .search-result-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--green-dark));
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .search-result-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .search-result-avatar .material-icons { font-size: 1rem; color: var(--text); }
        .search-result-username {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text);
        }

        /* Conversation list */
        .conv-list { display: flex; flex-direction: column; gap: 0.6rem; }

        .conv-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }

        .conv-item::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: linear-gradient(var(--blue), var(--green));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .conv-item:hover {
            border-color: rgba(26,111,255,0.4);
            box-shadow: 0 0 20px var(--blue-glow);
            transform: translateX(4px);
            color: inherit;
        }

        .conv-item:hover::before { opacity: 1; }

        .conv-item.unread {
            border-color: rgba(26,111,255,0.35);
            background: rgba(26,111,255,0.06);
        }

        .conv-item.unread::before { opacity: 1; }

        .conv-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--green-dark));
            border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .conv-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .conv-avatar .material-icons { font-size: 1.4rem; color: var(--text); }

        .conv-info { flex: 1; min-width: 0; }

        .conv-username {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
            letter-spacing: 0.5px;
        }

        .conv-last-msg {
            font-size: 0.85rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 0.2rem;
        }

        .conv-last-msg.unread-msg {
            color: var(--silver);
            font-weight: 600;
        }

        .conv-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .conv-time {
            font-family: var(--font-title);
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .unread-badge {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue));
            color: white;
            font-family: var(--font-title);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 0 10px var(--blue-glow);
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

<div class="messages-layout">

    <div class="messages-header">
        <div class="messages-title">
            <span class="material-icons">chat</span>
            Messages privés
        </div>
        <a href="/" class="back-link" style="margin-bottom:0">
            <span class="material-icons">arrow_back</span>
            Feed
        </a>
    </div>

    <!-- SEARCH BOX -->
    <div class="search-box">
        <div class="search-box-title">
            <span class="material-icons" style="font-size:0.9rem">person_search</span>
            Nouvelle conversation
        </div>
        <div class="search-input-wrap">
            <input type="text"
                   id="searchUser"
                   class="form-control"
                   placeholder="Rechercher un utilisateur..."
                   autocomplete="off">
        </div>
        <div class="search-results" id="searchResults"></div>
    </div>

    <!-- CONVERSATIONS -->
    <?php if (empty($conversations)): ?>
        <div class="empty-state">
            <span class="material-icons">chat_bubble_outline</span>
            <p>Aucune conversation</p>
            <p style="font-size:0.8rem;margin-top:0.5rem">
                Recherchez un utilisateur pour démarrer
            </p>
        </div>
    <?php else: ?>
        <div class="conv-list">
            <?php foreach ($conversations as $conv): ?>
            <?php $hasUnread = $conv['unread_count'] > 0; ?>
            <a href="/messages/<?= $conv['id'] ?>"
               class="conv-item <?= $hasUnread ? 'unread' : '' ?>">

                <div class="conv-avatar">
                    <?php if ($conv['other_avatar']): ?>
                        <img src="<?= htmlspecialchars($conv['other_avatar']) ?>" alt="avatar">
                    <?php else: ?>
                        <span class="material-icons">person</span>
                    <?php endif; ?>
                </div>

                <div class="conv-info">
                    <div class="conv-username">
                        <?= htmlspecialchars($conv['other_username']) ?>
                    </div>
                    <?php if ($conv['last_message']): ?>
                    <div class="conv-last-msg <?= $hasUnread ? 'unread-msg' : '' ?>">
                        <?php if ($conv['last_sender_id'] == \App\Core\Session::get('user_id')): ?>
                            <span style="color:var(--text-muted)">Vous : </span>
                        <?php endif; ?>
                        <?= htmlspecialchars(substr($conv['last_message'], 0, 60)) ?>
                        <?= strlen($conv['last_message']) > 60 ? '...' : '' ?>
                    </div>
                    <?php else: ?>
                    <div class="conv-last-msg">Conversation démarrée</div>
                    <?php endif; ?>
                </div>

                <div class="conv-meta">
                    <?php if ($conv['last_message_at']): ?>
                    <div class="conv-time">
                        <?= date('H:i', strtotime($conv['last_message_at'])) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($hasUnread): ?>
                    <div class="unread-badge"><?= $conv['unread_count'] ?></div>
                    <?php endif; ?>
                </div>

            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script src="/assets/js/main.js"></script>
<script>
// Search users
const searchInput   = document.getElementById('searchUser');
const searchResults = document.getElementById('searchResults');
let searchTimer;

searchInput.addEventListener('input', () => {
    clearTimeout(searchTimer);
    const q = searchInput.value.trim();

    if (q.length < 2) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
    }

    searchTimer = setTimeout(async () => {
        const res   = await fetch('/messages/search?q=' + encodeURIComponent(q));
        const users = await res.json();

        searchResults.innerHTML = '';

        if (users.length === 0) {
            searchResults.innerHTML = '<div style="padding:0.7rem;color:var(--text-muted);font-size:0.85rem">Aucun utilisateur trouvé</div>';
        } else {
            users.forEach(user => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <div class="search-result-avatar">
                        ${user.avatar
                            ? `<img src="${user.avatar}" alt="avatar">`
                            : '<span class="material-icons">person</span>'
                        }
                    </div>
                    <div class="search-result-username">${user.username}</div>
                    <span class="material-icons" style="margin-left:auto;color:var(--blue);font-size:1.1rem">send</span>
                `;
                item.addEventListener('click', () => {
                    window.location.href = '/messages/new?user_id=' + user.id;
                });
                searchResults.appendChild(item);
            });
        }

        searchResults.style.display = 'block';
    }, 300);
});

// Fermer search si clic extérieur
document.addEventListener('click', e => {
    if (!e.target.closest('.search-box')) {
        searchResults.style.display = 'none';
    }
});
</script>
</body>
</html>