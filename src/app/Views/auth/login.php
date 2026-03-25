<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        * { 
            box-sizing: border-box;
            margin: 0; 
            padding: 0; 
        }
        body { 
            font-family: Arial, sans-serif;
             background: #f0f2f5;
              display: flex;
               justify-content: center; 
               align-items: center;
                min-height: 100vh;
             }
        .card { background: white;
        padding: 2rem; 
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        width: 100%; max-width: 400px; 
        }
        h1 { text-align: center;
         margin-bottom: 1.5rem;
          color: #1877f2; 
        }
        .form-group { margin-bottom: 1rem; }
        label { display: block;
         margin-bottom: 0.3rem;
          font-weight: bold;
           font-size: 0.9rem;
         }
        input { width: 100%;
         padding: 0.7rem; 
         border: 1px solid #ddd;
          border-radius: 4px; 
          font-size: 1rem; 
        }
        button { width: 100%;
         padding: 0.8rem; 
         background: #1877f2;
          color: white; 
          border: none; 
          border-radius: 4px;
           font-size: 1rem;
            cursor: pointer;
             margin-top: 0.5rem;
            }
        button:hover { background: #166fe5; }
        .errors { background: #ffeaea;
         border: 1px solid #f44;
          padding: 0.8rem; 
          border-radius: 4px; 
          margin-bottom: 1rem;
         }
        .errors li { margin-left: 1rem;
         color: #c00;
          font-size: 0.9rem;
         }
        .success { 
            background: #eaffea;
         border: 1px solid #4c4; 
         padding: 0.8rem; 
         border-radius: 4px; 
         margin-bottom: 1rem; 
         color: #060; 
         font-size: 0.9rem; 
        }
        .link { 
            text-align: center; 
        margin-top: 1rem; 
        font-size: 0.9rem; 
        }
        .link a { color: #1877f2; 
        text-decoration: none; 
    }
    </style>
</head>
<body>
<div class="card">
    <h1>Connexion</h1>

    <?php if (App\Core\Session::get('success')): ?>
        <div class="success"><?= htmlspecialchars(App\Core\Session::get('success')) ?></div>
        <?php App\Core\Session::set('success', null); ?>
    <?php endif; ?>

    <?php if (App\Core\Session::get('errors')): ?>
        <ul class="errors">
            <?php foreach (App\Core\Session::get('errors') as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            <?php App\Core\Session::set('errors', []); ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/login">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
    <div class="link">
        Pas encore de compte ? <a href="/register">S'inscrire</a>
    </div>
</div>
</body>
</html>