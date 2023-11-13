# KeyHeaven_app

Ce projet est basé sur le framework Symfony et utilise Docker pour la gestion de l'environnement de développement. Suivez les étapes ci-dessous pour configurer votre environnement et démarrer le projet.

## Prérequis

- Docker
- Docker Compose

## Installation

1. **Build de l'image Docker PHP8 :**
   Naviguez vers le répertoire `docker/php8` et exécutez la commande suivante :
   ```bash
   cd docker/php8
   docker-compose build --no-cache
   docker compose up -d
   ```
    A la racine 
    ```bash
   docker compose up -d
   ```

2. **Exécuter le script .sh :**
```bash
.\init-local-keyHeaven.sh
```

Provide by chris