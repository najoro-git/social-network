<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Inscription</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-auth">

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <div class="auth-logo-title">SOCIALNET</div>
            <div class="auth-logo-sub">Créer un compte</div>
        </div>

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

        <form method="POST" action="/register">
            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">person</span>
                    Username
                </label>
                <input type="text" name="username" class="form-control"
                       placeholder="cyber_user" required autofocus
                       value="<?= htmlspecialchars(\App\Core\Session::get('old')['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">mail</span>
                    Email
                </label>
                <input type="email" name="email" class="form-control"
                       placeholder="user@network.io" required
                       value="<?= htmlspecialchars(\App\Core\Session::get('old')['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">lock</span>
                    Mot de passe
                </label>
                <input type="password" name="password" class="form-control"
                       placeholder="min. 6 caractères" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">lock_reset</span>
                    Confirmer le mot de passe
                </label>
                <input type="password" name="confirm_password" class="form-control"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:0.5rem">
                <span class="material-icons">rocket_launch</span>
                Créer le compte
            </button>
        </form>

        <div class="auth-divider"><span>ou</span></div>

        <div class="auth-footer">
            Déjà un compte ?
            <a href="/login">Se connecter</a>
        </div>

    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>