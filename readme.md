# Projet Symfony

## Prérequis

Assurez-vous d'avoir les outils suivants installés sur votre machine :

- **PHP** (version recommandée : 8.2 ou supérieure)
- **Symfony CLI**
- **Composer**
- **Node.js** et **npm**
- **Docker** et **Docker Compose**

### Installation des outils

Si vous ne disposez pas de ces outils, voici comment les installer :

1. **Installer PHP :**
    - Sur Linux :
      ```bash
      sudo apt update
      sudo apt install php php-cli php-mbstring php-xml php-curl php-zip php-intl
      ```
    - Sur macOS (via Homebrew) :
      ```bash
      brew install php
      ```

2. **Installer Symfony CLI :**
   ```bash
   curl -sS https://get.symfony.com/cli/installer | bash
   mv ~/.symfony*/bin/symfony /usr/local/bin/symfony
   ```

3. **Installer Composer :**
   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   sudo mv composer.phar /usr/local/bin/composer
   php -r "unlink('composer-setup.php');"
   ```

4. **Installer Node.js et npm :**
    - Sur Linux :
      ```bash
      sudo apt update
      sudo apt install nodejs npm
      ```
    - Sur macOS (via Homebrew) :
      ```bash
      brew install node
      ```

5. **Installer Docker et Docker Compose :**
    - Suivez les instructions officielles : [Installer Docker](https://docs.docker.com/get-docker/)

## Installation

1. **Cloner le dépôt :**

   ```bash
   git clone <URL_DU_DEPOT>
   cd <NOM_DU_PROJET>
   ```

2. **Installer les dépendances PHP :**

   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript :**

   ```bash
   npm install
   ```

4. **Configurer les variables d'environnement :**

   Copiez le fichier `.env` en `.env.local` pour personnaliser la configuration locale.

   ```bash
   cp .env .env.local
   ```

5. **Lancer le serveur Symfony :**

   ```bash
   symfony server:start --no-tls -d
   ```

6. **Démarrer les services Docker (Base de données et Mercure) :**

   ```bash
   docker compose up -d
   ```

7. **Lancer le watcher pour les assets :**

   ```bash
   npm run watch
   ```

## Gestion de la base de données

1. **Exécuter les migrations :**

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

2. **Charger les fixtures (données de développement) :**

   ```bash
   php bin/console doctrine:fixtures:load
   ```

## Accéder à l'application

Une fois les services lancés, l'application est accessible à l'adresse :

```
http://127.0.0.1:8000
```

## Arrêter les services

Pour arrêter le serveur Symfony et les conteneurs Docker :

```bash
symfony server:stop

docker compose down
```

