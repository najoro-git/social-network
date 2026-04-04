<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Publication</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-main">

<!-- NAVBAR -->
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

<div class="container">

    <a href="/" class="back-link">
        <span class="material-icons">arrow_back</span>
        Retour au feed
    </a>

    <!-- POST -->
    <div class="post-card" style="margin-bottom:1.5rem">
        <div class="post-header">
            <?php if ($post['avatar']): ?>
                <img src="<?= htmlspecialchars($post['avatar']) ?>"
                     class="post-avatar" alt="avatar">
            <?php else: ?>
                <div class="post-avatar-placeholder">
                    <span class="material-icons">person</span>
                </div>
            <?php endif; ?>

            <div class="post-meta">
                <div class="post-username"><?= htmlspecialchars($post['username']) ?></div>
                <div class="post-time">
                    <span class="material-icons" style="font-size:0.8rem;vertical-align:middle">schedule</span>
                    <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                    <?php if ($post['is_edited']): ?>
                        <span class="post-edited">· modifié</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($post['user_id'] == \App\Core\Session::get('user_id')): ?>
            <div class="post-actions">
                <a href="/posts/edit?id=<?= $post['id'] ?>" class="action-btn action-btn-edit">
                    <span class="material-icons">edit</span>
                </a>
                <form method="POST" action="/posts/delete" class="form-delete">
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                    <button type="submit" class="action-btn action-btn-delete">
                        <span class="material-icons">delete</span>
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <div class="post-body"><?= htmlspecialchars($post['content']) ?></div>

        <?php if ($post['image']): ?>
            <img src="<?= htmlspecialchars($post['image']) ?>"
                 class="post-image" alt="image">
        <?php endif; ?>

        <div class="post-footer">
            <?php if (\App\Core\Auth::check()): ?>
            <button class="like-btn <?= $liked ? 'liked' : '' ?>"
                    data-post-id="<?= $post['id'] ?>">
                <span class="material-icons">
                    <?= $liked ? 'favorite' : 'favorite_border' ?>
                </span>
                <span class="like-count"><?= $likeCount ?></span>
            </button>
            <?php endif; ?>

            <span class="comment-link" style="cursor:default">
                <span class="material-icons">chat_bubble_outline</span>
                <span><?= $totalCom ?></span>
            </span>
        </div>
    </div>

    <!-- COMMENTS SECTION -->
    <div class="comments-section">

        <div class="comments-title">
            <span class="material-icons">forum</span>
            Commentaires (<?= $totalCom ?>)
        </div>

        <!-- ERRORS -->
        <?php if (\App\Core\Session::get('errors')): ?>
            <div class="alert alert-error">
                <span class="material-icons">error</span>
                <ul>
                    <?php foreach (\App\Core\Session::get('errors') as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php \App\Core\Session::set('errors', []); ?>
        <?php endif; ?>

        <!-- COMMENT FORM -->
        <form class="comment-form" method="POST" action="/comments/create">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

            <div class="comment-avatar">
                <?php if (\App\Core\Session::get('avatar')): ?>
                    <img src="<?= htmlspecialchars(\App\Core\Session::get('avatar')) ?>" alt="avatar">
                <?php else: ?>
                    <span class="material-icons">person</span>
                <?php endif; ?>
            </div>

            <div class="comment-input-wrap">
                <textarea
                    id="commentContent"
                    name="content"
                    class="form-control"
                    maxlength="500"
                    placeholder="Écrire un commentaire..."></textarea>
                <div class="comment-form-footer">
                    <span class="char-counter" id="commentCounter">0 / 500</span>
                    <button type="submit" class="btn btn-blue" style="padding:0.4rem 1rem;font-size:0.85rem">
                        <span class="material-icons">send</span>
                        Commenter
                    </button>
                </div>
            </div>
        </form>

        <!-- COMMENTS LIST -->
        <?php if (empty($comments)): ?>
            <div class="empty-state" style="padding:2rem">
                <span class="material-icons">chat_bubble_outline</span>
                <p>Aucun commentaire</p>
                <p style="font-size:0.78rem;margin-top:0.3rem">Soyez le premier !</p>
            </div>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>
            <div class="comment-item">
                <div class="comment-avatar">
                    <?php if ($c['avatar']): ?>
                        <img src="<?= htmlspecialchars($c['avatar']) ?>" alt="avatar">
                    <?php else: ?>
                        <span class="material-icons">person</span>
                    <?php endif; ?>
                </div>

                <div class="comment-bubble">
                    <div class="comment-bubble-username"><?= htmlspecialchars($c['username']) ?></div>
                    <div class="comment-bubble-text"><?= htmlspecialchars($c['content']) ?></div>
                    <div class="comment-bubble-meta">
                        <span class="comment-bubble-time">
                            <span class="material-icons" style="font-size:0.75rem;vertical-align:middle">schedule</span>
                            <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                        </span>
                        <?php if ($c['user_id'] == \App\Core\Session::get('user_id')): ?>
                        <form method="POST" action="/comments/delete" class="form-delete">
                            <input type="hidden" name="id"      value="<?= $c['id'] ?>">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <button type="submit" class="action-btn action-btn-delete"
                                    style="font-size:0.75rem;padding:0.2rem 0.4rem">
                                <span class="material-icons" style="font-size:0.9rem">delete</span>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/posts/<?= $post['id'] ?>?page=<?= $i ?>"
                       class="<?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

</div>

<script src="/assets/js/main.js"></script>
</body>
</html>