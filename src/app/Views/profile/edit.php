<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — Modifier le profil</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-profile">

<!-- NAVBAR -->
<<nav class="navbar">
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

    <a href="/profile" class="back-link">
        <span class="material-icons">arrow_back</span>
        Retour au profil
    </a>

    <div class="card">
        <div style="padding:1.5rem">

            <!-- TITLE -->
            <div style="font-family:var(--font-title);font-size:0.9rem;color:var(--gold);
                        letter-spacing:2px;text-transform:uppercase;margin-bottom:1.5rem;
                        display:flex;align-items:center;gap:0.5rem">
                <span class="material-icons">manage_accounts</span>
                Modifier le profil
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

            <!-- AVATAR PREVIEW -->
            <?php if ($user['avatar']): ?>
            <div style="margin-bottom:1.2rem;display:flex;align-items:center;gap:1rem">
                <img src="<?= htmlspecialchars($user['avatar']) ?>"
                     style="width:70px;height:70px;border-radius:50%;object-fit:cover;
                            border:2px solid var(--green);box-shadow:0 0 12px var(--green-glow)">
                <div>
                    <div style="font-family:var(--font-title);font-size:0.75rem;
                                color:var(--text-muted);letter-spacing:1px">AVATAR ACTUEL</div>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="/profile/edit" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="form-label">
                        <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">person</span>
                        Username
                    </label>
                    <input type="text" name="username" class="form-control"
                           value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">info</span>
                        Bio
                    </label>
                    <textarea name="bio" class="form-control"
                              rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="material-icons" style="font-size:0.9rem;vertical-align:middle">add_a_photo</span>
                        Nouvel avatar
                    </label>
                    <label class="image-attach" style="width:100%;justify-content:center;padding:0.8rem">
                        <span class="material-icons">upload</span>
                        <span>Choisir une image (max 2MB)</span>
                        <input type="file" id="image" name="avatar" accept="image/*">
                    </label>
                    <div id="imagePreview" style="margin-top:0.8rem;text-align:center"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span class="material-icons">save</span>
                    Enregistrer
                </button>

            </form>
        </div>
    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>