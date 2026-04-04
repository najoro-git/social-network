<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Feed</title>
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
        <a href="/profile"><span class="material-icons">account_circle</span><span><?= htmlspecialchars(\App\Core\Session::get('username') ?? 'Profil') ?></span></a>
        <a href="/logout" class="btn-logout"><span class="material-icons">power_settings_new</span><span>Quitter</span></a>
    </div>
</nav>

<div class="container">

    <!-- ALERTS -->
    <?php if (\App\Core\Session::get('errors')): ?>
        <div class="alert alert-error">
            <span class="material-icons">error</span>
            <ul>
                <?php foreach (\App\Core\Session::get('errors') as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php \App\Core\Session::set('errors', []); ?>
    <?php endif; ?>

    <?php if (\App\Core\Session::get('success')): ?>
        <div class="alert alert-success">
            <span class="material-icons">check_circle</span>
            <?= htmlspecialchars(\App\Core\Session::get('success')) ?>
        </div>
        <?php \App\Core\Session::set('success', null); ?>
    <?php endif; ?>

    <!-- CREATE POST -->
    <?php if (\App\Core\Auth::check()): ?>
    <div class="create-post">
        <div class="create-post-header">
            <span class="material-icons" style="color:var(--green)">edit</span>
            <span class="create-post-label">Nouvelle publication</span>
        </div>

        <form method="POST" action="/posts/create" enctype="multipart/form-data">
            <textarea
                id="content"
                name="content"
                class="form-control"
                placeholder="Que se passe-t-il dans le réseau ?"
                maxlength="500"
                rows="3"></textarea>
            <div class="char-counter" id="contentCounter">0 / 500</div>

            <div class="create-post-footer">
                <label class="image-attach">
                    <span class="material-icons">add_photo_alternate</span>
                    <span>Photo</span>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                </label>
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">send</span>
                    Publier
                </button>
            </div>
            <div id="imagePreview"></div>
        </form>
    </div>
    <?php endif; ?>

    <!-- FEED -->
    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <span class="material-icons">dynamic_feed</span>
            <p>Aucune publication pour le moment</p>
            <p style="margin-top:0.5rem;font-size:0.8rem">Soyez le premier à publier !</p>
        </div>
    <?php else: ?>

        <?php foreach ($posts as $post): ?>
        <?php
            $likeInfo    = $likesData[$post['id']] ?? ['total' => 0, 'user_liked' => false];
            $liked       = $likeInfo['user_liked'];
            $likeCount   = $likeInfo['total'];
            $commentCount= $commentsData[$post['id']] ?? 0;
        ?>
        <div class="post-card">

            <!-- POST HEADER -->
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

                <!-- ACTIONS (auteur seulement) -->
                <?php if (\App\Core\Auth::check() && $post['user_id'] == \App\Core\Session::get('user_id')): ?>
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

            <!-- POST BODY -->
            <div class="post-body"><?= htmlspecialchars($post['content']) ?></div>

            <!-- POST IMAGE -->
            <?php if ($post['image']): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>"
                     class="post-image" alt="image">
            <?php endif; ?>

            <!-- POST FOOTER -->
            <div class="post-footer">
                <!-- Like -->
                <?php if (\App\Core\Auth::check()): ?>
                <button class="like-btn <?= $liked ? 'liked' : '' ?>"
                        data-post-id="<?= $post['id'] ?>">
                    <span class="material-icons">
                        <?= $liked ? 'favorite' : 'favorite_border' ?>
                    </span>
                    <span class="like-count"><?= $likeCount ?></span>
                </button>
                <?php endif; ?>

                <!-- Commentaires -->
                <a href="/posts/<?= $post['id'] ?>" class="comment-link">
                    <span class="material-icons">chat_bubble_outline</span>
                    <span><?= $commentCount ?></span>
                </a>
            </div>

        </div>
        <?php endforeach; ?>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/?page=<?= $i ?>"
                   class="<?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<script src="/assets/js/main.js"></script>
</body>
</html>