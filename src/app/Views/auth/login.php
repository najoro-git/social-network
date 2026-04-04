<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Connexion</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-auth">

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <div class="auth-logo-title">SOCIALNET</div>
            <div class="auth-logo-sub">Cyberpunk Network v1.0</div>
        </div>

        <?php if (\App\Core\Session::get('success')): ?>
            <div class="alert alert-success">
                <span class="material-icons">check_circle</span>
                <?= htmlspecialchars(\App\Core\Session::get('success')) ?>
            </div>
            <?php \App\Core\Session::set('success', null); ?>
        <?php endif; ?>

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

        <form method="POST" action="/login">
            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">mail</span>
                    Email
                </label>
                <input type="email" name="email" class="form-control"
                       placeholder="user@network.io" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">lock</span>
                    Mot de passe
                </label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:0.5rem">
                <span class="material-icons">login</span>
                Connexion
            </button>
        </form>

        <div class="auth-divider"><span>ou</span></div>

        <div class="auth-footer">
            Pas encore de compte ?
            <a href="/register">Créer un compte</a>
        </div>

    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>