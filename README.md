## Intallation serveur Symfony

### 1- Cloner le projet

```sh
git clone https://github.com/Eloool/Projet-Web-Musique.git
```

### 2- Installer les dépendances 

```sh
composer install
composer require nelmio/api-doc-bundle
```



### 3- Configurer le ficher .env

Dupliquer le ficher env et le renomer .env.local' et décommenter cette ligne

```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

### 4- Lancer la base de donnnés

```sh
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate
```

### 5- Lancer le serveur

```sh
symfony serve
```
