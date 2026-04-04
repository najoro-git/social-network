<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Modifier la publication</title>
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

<div class="container-sm" style="padding-top:2rem">

    <a href="/" class="back-link">
        <span class="material-icons">arrow_back</span>
        Retour au feed
    </a>

    <div class="card">
        <div style="padding:1.5rem">

            <!-- TITLE -->
            <div style="font-family:var(--font-title);font-size:0.9rem;color:var(--gold);
                        letter-spacing:2px;text-transform:uppercase;margin-bottom:1.5rem;
                        display:flex;align-items:center;gap:0.5rem">
                <span class="material-icons">edit_note</span>
                Modifier la publication
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

            <form method="POST" action="/posts/edit">
                <input type="hidden" name="id" value="<?= $post['id'] ?>">

                <div class="form-group">
                    <label class="form-label">
                        <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">article</span>
                        Contenu
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        class="form-control"
                        maxlength="500"
                        rows="5"><?= htmlspecialchars($post['content']) ?></textarea>
                    <div class="char-counter" id="contentCounter">0 / 500</div>
                </div>

                <div style="display:flex;gap:0.8rem">
                    <a href="/" class="btn btn-ghost" style="flex:1;justify-content:center">
                        <span class="material-icons">close</span>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex:2">
                        <span class="material-icons">save</span>
                        Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>