<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Profil</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-profile">

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="navbar-brand">
        <span class="material-icons">hub</span>
        SOCIALNET
    </a>
    <div class="navbar-links">
        <a href="/"><span class="material-icons">home</span><span>Feed</span></a>
        <a href="/profile"><span class="material-icons">account_circle</span><span>Profil</span></a>
        <a href="/logout" class="btn-logout"><span class="material-icons">power_settings_new</span><span>Quitter</span></a>
    </div>
</nav>

<div class="container">

    <?php if (\App\Core\Session::get('success')): ?>
        <div class="alert alert-success">
            <span class="material-icons">check_circle</span>
            <?= htmlspecialchars(\App\Core\Session::get('success')) ?>
        </div>
        <?php \App\Core\Session::set('success', null); ?>
    <?php endif; ?>

    <!-- PROFILE CARD -->
    <div class="card" style="margin-bottom:1.5rem">
        <div class="profile-cover"></div>
        <div class="profile-body">
            <div class="profile-avatar-wrap">
                <?php if ($user['avatar']): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>"
                         class="profile-avatar" alt="avatar">
                <?php else: ?>
                    <div class="profile-avatar-placeholder">
                        <span class="material-icons">person</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-username"><?= htmlspecialchars($user['username']) ?></div>
            <div class="profile-bio"><?= htmlspecialchars($user['bio'] ?? 'Aucune bio.') ?></div>
            <div class="profile-meta">
                <span class="material-icons" style="font-size:0.9rem">calendar_today</span>
                Membre depuis <?= date('d/m/Y', strtotime($user['created_at'])) ?>
            </div>

            <div style="margin-top:1.2rem">
                <a href="/profile/edit" class="btn btn-gold">
                    <span class="material-icons">edit</span>
                    Modifier le profil
                </a>
            </div>
        </div>
    </div>

</div>

<script src="/assets/js/main.js"></script>
</body>
</html>