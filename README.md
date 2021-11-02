# O'Berge'Inn
[![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/built-by-developers.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/made-with-javascript.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/uses-css.svg)](https://forthebadge.com) [![forthebadge](https://forthebadge.com/images/badges/uses-html.svg)](https://forthebadge.com)




Ce dépôt contient le code de l'application O'Berge'Inn qui permet d'organiser et de gérer nimporte quel événement (sportif/festif). 

## Screens
Cette application est responsive. 
Voici quelques exemples de screens de l'application.

![O'Berge'Inn Accueil](obergeinn/docs/screens/accueil.png "O'Berge'Inn Accueil")

![O'Berge'Inn Tableau de bord](obergeinn/docs/screens/dashboard.png "O'Berge'Inn Tableau de bord")

![O'Berge'Inn Liste des événements](obergeinn/docs/screens/events-list.png "O'Berge'Inn Liste des événements")

![O'Berge'Inn Menu](obergeinn/docs/screens/mobile-menu.png "O'Berge'Inn Menu")

![O'Berge'Inn Création d'un événement](obergeinn/docs/screens/mobile-event-create.png "O'Berge'Inn Création d'un événement")


## Pour commencer
Ces instructions vont vous permettre d'obtenir une copie fonctionnelle du projet sur votre poste de travail.
### Pré-requis
Afin de pouvoir exécuter l'application sur votre poste, vous devez d'abord installer les dépendances suivantes : 
* Intervention Image
* Twig-Bundle
* Google-Mailer

### Installation
Pour installer la dépendance Intervention image : https://github.com/Intervention/image \
Twig-Bundle : `composer require symfony/twig-bundle` \
Google-mailer : `composer require symfony/google-mailer`

## Démarrage
- Faire un composer install
- Configurer/créer le dossier .env.local 
    - Ne pas oublier le mailer DSN : 
```
###> symfony/google-mailer ###
# Gmail SHOULD NOT be used on production, use it in development only.
MAILER_DSN=gmail+smtp://EMAIL:PASSWORD@default?verify_peer=0
###< symfony/google-mailer ###
```
Si vous utilisez Gmail, il vous faut créer un mot de passe d'application dans les paramètres de votre compte Gmail.
https://support.google.com/accounts/answer/185833?hl=fr
- Configurer/créer la BDD 
  - `php bin/console doctrine:database:create`
- Have fun with the conflicts

## Versions
1.0

## Auteurs:
- Morgane Alemany https://github.com/morganealemany