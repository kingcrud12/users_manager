# Users manager

![Symfony](https://img.shields.io/badge/Symfony-7.4.x-green)
![Doctrine](https://img.shields.io/badge/Doctrine.x-red)
![Mailjet](https://img.shields.io/badge/Mailhet.x-blue)
![PHP](https://img.shields.io/badge/PHP-8.x-blueviolet)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

**Users manager** est une API qui assure : 

- Création de compte utilisateurs,
- Connexion,
- Déconnexion,
- Authentification JWT
- Reset de mot de passe via un lien recu par mail
- Notification par mail
- RBAC

---

## 🚀 Installation

### Prérequis
- PHP >= 8.1  
- Composer  
- Symfony CLI (optionnel mais recommandé)  
- MySQL ou PostgreSQL  
- Node.js & Yarn (pour gérer éventuellement les assets)  

### Étapes d’installation

```bash
# Cloner le projet
git clone <repo-url>
cd users-manager-api

# Installer les dépendances PHP
composer install

# Configurer l'environnement
cp .env .env.local
# Modifier .env.local avec vos infos DB et mailer (MAILER_DSN, DATABASE_URL, JWT_PASSPHRASE)

# Générer la clé JWT
php bin/console lexik:jwt:generate-keypair

# Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur de développement
symfony server:start

📂 Structure du projet

src/
├─ Controller/         # Contrôleurs (auth, user, password reset)
├─ Entity/             # Entités Doctrine (User, ResetPasswordRequest)
├─ Repository/         # Repositories Doctrine
├─ Security/           # Gestion JWT & Authenticator
├─ Service/            # Services métiers (Email, PasswordReset, User)
└─ EventSubscriber/    # Gestion des événements (ex: envoi mail reset)

🔑 API         Endpoints
Méthode	       Endpoint	                Description	                                   Auth
POST	       /auth/register	        Créer un compte utilisateur	                    ❌
POST	       /auth/login	            Connexion utilisateur, retour token JWT	        ❌
GET	           /user/me	                Récupérer le profil de l’utilisateur connecté	✅
POST	      /auth/forgot-password	    Demander un email de réinitialisation	        ❌
POST	     /auth/reset-password	    Réinitialiser le mot de passe via le lien	    ❌

🔒 Authentification
L’API utilise JWT (JSON Web Token) pour sécuriser les endpoints.

Après un login réussi, un token est renvoyé :

json
Copier le code
{
  "token": "eyJ0eXAiOiJKV1QiLCJh..."
}
Utilisez ce token dans vos requêtes :

http
GET /user/me
Authorization: Bearer <token>
📧 Réinitialisation du mot de passe
L’utilisateur appelle /auth/forgot-password avec son email.

Un email est envoyé avec un lien sécurisé contenant un token.

L’utilisateur clique et appelle /auth/reset-password pour définir un nouveau mot de passe.

▶️ Utilisation rapide
Créer un compte via /auth/register

Se connecter avec /auth/login et récupérer un token JWT

Accéder à ses infos avec /user/me (JWT requis)

Mot de passe oublié → /auth/forgot-password

Réinitialiser via /auth/reset-password

🤝 Contribuer
Forker le projet

Créer une branche (git checkout -b feature/ma-feature)

Commit (git commit -m "Ajout d'une nouvelle feature")

Push (git push origin feature/ma-feature)

Ouvrir une Pull Request

```

📄 Licence
Projet sous licence MIT.
© 2025 Users Manager API




