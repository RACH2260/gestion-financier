<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Gestion Financière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .brand-icon {
            background: #4e54c8;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        .btn-primary {
            background: #4e54c8;
            border: none;
            padding: 0.8rem;
            border-radius: 10px;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.7rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center">
        <div class="brand-icon">
            <i class="fas fa-vault"></i>
        </div>
        <h4 class="fw-bold mb-4">Gestion Financière</h4>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label text-muted small">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-muted small">Mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rem">
                <label class="form-check-label small" for="rem">Se souvenir</label>
            </div>
            <a href="{{ route('password.request') }}" class="small text-decoration-none">Oublié ?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold">SE CONNECTER</button>

        <div class="text-center mt-4">
            <span class="small text-muted">Pas de compte ?</span>
            <a href="{{ route('register') }}" class="small text-decoration-none fw-bold">S'inscrire</a>
        </div>
    </form>
</div>

</body>
</html>