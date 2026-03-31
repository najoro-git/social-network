<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — <?= htmlspecialchars($user['username']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        .navbar { background: #1877f2; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }
        .navbar .links a { margin-left: 1rem; font-weight: normal; }
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .cover { background: #1877f2; height: 120px; }
        .profile-body { padding: 1rem 2rem 2rem; }
        .avatar-wrap { margin-top: -50px; margin-bottom: 1rem; }
        .avatar-wrap img { width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; object-fit: cover; }
        .avatar-wrap .no-avatar { width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; background: #ccc; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; }
        h1 { font-size: 1.5rem; color: #333; }
        .bio { color: #666; margin: 0.5rem 0 1rem; }
        .meta { font-size: 0.85rem; color: #999; }
        .btn { display: inline-block; padding: 0.6rem 1.2rem; background: #1877f2; color: white; border-radius: 4px; text-decoration: none; font-size: 0.9rem; margin-top: 1rem; }
        .btn:hover { background: #166fe5; }
        .success { background: #eaffea; border: 1px solid #4c4; padding: 0.8rem; border-radius: 4px; margin-bottom: 1rem; color: #060; font-size: 0.9rem; }
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

    <?php if (\App\Core\Session::get('success')): ?>
        <div class="success"><?= htmlspecialchars(\App\Core\Session::get('success')) ?></div>
        <?php \App\Core\Session::set('success', null); ?>
    <?php endif; ?>

    <div class="card">
        <div class="cover"></div>
        <div class="profile-body">
            <div class="avatar-wrap">
                <?php if ($user['avatar']): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="avatar">
                <?php else: ?>
                    <div class="no-avatar">👤</div>
                <?php endif; ?>
            </div>
            <h1><?= htmlspecialchars($user['username']) ?></h1>
            <p class="bio"><?= htmlspecialchars($user['bio'] ?? 'Aucune bio.') ?></p>
            <p class="meta">Membre depuis : <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
            <a href="/profile/edit" class="btn">✏️ Modifier le profil</a>
        </div>
    </div>

</div>
</body>
</html>
