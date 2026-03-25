<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le profil</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        .navbar { background: #1877f2; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }
        .navbar .links a { margin-left: 1rem; font-weight: normal; }
        .container { max-width: 500px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 1.5rem; color: #333; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.3rem; font-weight: bold; font-size: 0.9rem; }
        input, textarea { width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        textarea { resize: vertical; min-height: 100px; }
        .avatar-preview { margin-bottom: 1rem; }
        .avatar-preview img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #1877f2; }
        button { width: 100%; padding: 0.8rem; background: #1877f2; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #166fe5; }
        .errors { background: #ffeaea; border: 1px solid #f44; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; }
        .errors li { margin-left: 1rem; color: #c00; font-size: 0.9rem; }
        .back { display: inline-block; margin-bottom: 1rem; color: #1877f2; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/">🌐 SocialNet</a>
    <div class="links">
        <a href="/profile">Mon profil</a>
        <a href="/logout">Déconnexion</a>
    </div>
</nav>

<div class="container">
    <a href="/profile" class="back">← Retour au profil</a>

    <div class="card">
        <h1>✏️ Modifier le profil</h1>

        <?php if (\App\Core\Session::get('errors')): ?>
            <ul class="errors">
                <?php foreach (\App\Core\Session::get('errors') as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
                <?php \App\Core\Session::set('errors', []); ?>
            </ul>
        <?php endif; ?>

        <?php if ($user['avatar']): ?>
            <div class="avatar-preview">
                <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="avatar actuel">
                <small>Avatar actuel</small>
            </div>
        <?php endif; ?>

        <form method="POST" action="/profile/edit" enctype="multipart/form-data">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Avatar (jpg, png, gif, webp — max 2MB)</label>
                <input type="file" name="avatar" accept="image/*">
            </div>
            <button type="submit">💾 Enregistrer</button>
        </form>
    </div>
</div>
</body>
</html>