<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Gestion Financière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            padding: 2rem;
        }
        .brand-icon {
            background: #4e54c8;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.2rem;
        }
        .btn-primary {
            background: #4e54c8;
            border: none;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: bold;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.6rem;
            border: 1px solid #dee2e6;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center">
        <div class="brand-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <h4 class="fw-bold mb-4">Créer un compte</h4>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label small">Nom complet</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Abdoul Rachid" required>
            @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small">Adresse Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nom@exemple.com" required>
            @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small">Mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label small">Confirmation</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">
            S'INSCRIRE
        </button>

        <div class="text-center mt-4">
            <span class="small text-muted">Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="small text-decoration-none fw-bold">Se connecter</a>
        </div>
    </form>
</div>

</body>
</html>