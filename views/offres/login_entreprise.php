<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
        background-attachment: fixed;
        padding: 2rem;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        width: 100%;
        max-width: 500px;
        padding: 3.5rem;
        transition: transform 0.3s ease;
    }
    .login-card:hover {
        transform: translateY(-5px);
    }
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .login-header h2 {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }
    .login-header p {
        color: #64748b;
        font-size: 1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
    }
    .form-control {
        border-radius: 1.2rem;
        padding: 1rem 1.5rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-size: 1rem;
        background: white;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .btn-login {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        border-radius: 1.2rem;
        padding: 1rem;
        width: 100%;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
        opacity: 0.9;
    }
    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 1rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        text-align: center;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Espace Entreprise</h2>
            <p>Connectez-vous pour publier vos offres</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-danger">
                Veuillez remplir tous les champs correctement.
            </div>
        <?php endif; ?>

        <form action="index.php?action=handleLogin" method="POST">
            <div class="form-group">
                <label class="form-label" for="nom_entreprise">Nom de l'entreprise</label>
                <input type="text" id="nom_entreprise" name="nom_entreprise" class="form-control" placeholder="Ex: Career Lab Inc." required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email_entreprise">Email de l'entreprise</label>
                <input type="email" id="email_entreprise" name="email_entreprise" class="form-control" placeholder="contact@entreprise.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_entreprise">Mot de passe</label>
                <input type="password" id="password_entreprise" name="password_entreprise" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-login">Se connecter</button>
        </form>
    </div>
</div>
