<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Feed</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        .navbar { background: #1877f2; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }
        .navbar .links a { margin-left: 1rem; font-weight: normal; }
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }

        /* Formulaire création post */
        .create-post { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .create-post textarea { width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: none; min-height: 80px; }
        .create-post textarea:focus { outline: none; border-color: #1877f2; }
        .post-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 0.8rem; }
        .post-actions label { cursor: pointer; color: #1877f2; font-size: 0.9rem; }
        .post-actions input[type="file"] { display: none; }
        .post-actions button { padding: 0.5rem 1.5rem; background: #1877f2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; }
        .post-actions button:hover { background: #166fe5; }
        .counter { font-size: 0.8rem; color: #999; }
        .counter.warn { color: #e74c3c; }
        .image-preview { margin-top: 0.8rem; }
        .image-preview img { max-width: 100%; max-height: 200px; border-radius: 4px; }

        /* Posts */
        .post { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 1rem; overflow: hidden; }
        .post-header { display: flex; align-items: center; padding: 1rem; }
        .post-header img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; margin-right: 0.8rem; }
        .post-header .no-avatar { width: 45px; height: 45px; border-radius: 50%; background: #1877f2; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; margin-right: 0.8rem; flex-shrink: 0; }
        .post-header .info strong { display: block; color: #333; }
        .post-header .info span { font-size: 0.8rem; color: #999; }
        .post-content { padding: 0 1rem 1rem; color: #333; line-height: 1.5; white-space: pre-wrap; }
        .post-image { width: 100%; max-height: 400px; object-fit: cover; }
        .post-footer { padding: 0.5rem 1rem; border-top: 1px solid #f0f2f5; display: flex; gap: 0.5rem; }
        .post-footer a { font-size: 0.85rem; color: #1877f2; text-decoration: none; padding: 0.3rem 0.6rem; border-radius: 4px; }
        .post-footer a:hover { background: #f0f2f5; }
        .post-footer form { display: inline; }
        .post-footer button { font-size: 0.85rem; color: #e74c3c; background: none; border: none; cursor: pointer; padding: 0.3rem 0.6rem; border-radius: 4px; }
        .post-footer button:hover { background: #ffeaea; }
        .edited { font-size: 0.75rem; color: #999; font-style: italic; }

        /* Erreurs / succès */
        .errors { background: #ffeaea; border: 1px solid #f44; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; }
        .errors li { margin-left: 1rem; color: #c00; font-size: 0.9rem; }
        .success { background: #eaffea; border: 1px solid #4c4; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; color: #060; }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin: 1.5rem 0; }
        .pagination a { padding: 0.5rem 1rem; background: white; border-radius: 4px; text-decoration: none; color: #1877f2; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
        .pagination a.active { background: #1877f2; color: white; }
        .pagination a:hover { background: #e7f0ff; }

        /* Empty state */
        .empty { text-align: center; padding: 3rem; color: #999; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/">🌐 SocialNet</a>
    <div class="links">
        <a href="/profile">👤 <?= htmlspecialchars(\App\Core\Session::get('username') ?? 'Profil') ?></a>
        <a href="/logout">Déconnexion</a>
    </div>
</nav>

<div class="container">

    <?php if (\App\Core\Session::get('errors')): ?>
        <ul class="errors">
            <?php foreach (\App\Core\Session::get('errors') as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            <?php \App\Core\Session::set('errors', []); ?>
        </ul>
    <?php endif; ?>

    <?php if (\App\Core\Session::get('success')): ?>
        <div class="success"><?= htmlspecialchars(\App\Core\Session::get('success')) ?></div>
        <?php \App\Core\Session::set('success', null); ?>
    <?php endif; ?>

    <!-- Formulaire création post -->
    <?php if (\App\Core\Auth::check()): ?>
    <div class="create-post">
        <form method="POST" action="/posts/create" enctype="multipart/form-data">
            <textarea name="content" id="content" maxlength="500" placeholder="Quoi de neuf ?"></textarea>
            <div class="post-actions">
                <div>
                    <label for="image">📷 Photo</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <span class="counter" id="counter">0 / 500</span>
                    <button type="submit">Publier</button>
                </div>
            </div>
            <div class="image-preview" id="imagePreview"></div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Feed -->
    <?php if (empty($posts)): ?>
        <div class="empty">
            <p>Aucune publication pour le moment.</p>
            <p>Soyez le premier à publier !</p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <?php if ($post['avatar']): ?>
                    <img src="<?= htmlspecialchars($post['avatar']) ?>" alt="avatar">
                <?php else: ?>
                    <div class="no-avatar">👤</div>
                <?php endif; ?>
                <div class="info">
                    <strong><?= htmlspecialchars($post['username']) ?></strong>
                    <span>
                        <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                        <?php if ($post['is_edited']): ?>
                            <span class="edited">· modifié</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>

            <?php if ($post['image']): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" class="post-image" alt="image">
            <?php endif; ?>

            <?php if (\App\Core\Auth::check() && $post['user_id'] == \App\Core\Session::get('user_id')): ?>
            <div class="post-footer">
                <a href="/posts/edit?id=<?= $post['id'] ?>">✏️ Modifier</a>
                <form method="POST" action="/posts/delete"
                      onsubmit="return confirm('Supprimer cette publication ?')">
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                    <button type="submit">🗑️ Supprimer</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/?page=<?= $i ?>" class="<?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script>
    // Compteur caractères
    const textarea = document.getElementById('content');
    const counter  = document.getElementById('counter');
    if (textarea) {
        textarea.addEventListener('input', () => {
            const len = textarea.value.length;
            counter.textContent = len + ' / 500';
            counter.classList.toggle('warn', len > 450);
        });
    }

    // Preview image
    const imageInput   = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    if (imageInput) {
        imageInput.addEventListener('change', () => {
            imagePreview.innerHTML = '';
            const file = imageInput.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                imagePreview.appendChild(img);
            }
        });
    }
</script>
</body>
</html>