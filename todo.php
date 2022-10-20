<?php
/*
//Work In Progress : Tout mettre dans un dossier "core" puis créer Api class et WebApp class qui hériterons de Application class pour avoir une utilisation encore plus simple (instance du router automatique, dotenv automatique, gestion des requêtes différentes ?, l'api return obligatoirement une JsonReponse...)

//TODO: Gestion de la documentation native pour l'api (possibilité de mettre très facilement en place une documentation ou de laisser une documentation par défaut; Inspiré du microframework python FastApi)

//TODO: Vérifier les Entity (créer une method flush() dans la classe abstraite qui vérifiera que les columns correspondent bien et que le flush ne passe pas de valeur en trop, de mauvais type et vérifier la taille)

//TODO: Module de sécurité pour toute les valeurs passées en db

//TODO: Formulaire html sécurisé et plus simple (Surtout pour la gestion des CSRF)

//TODO: Automatiser le router (Chercher les controllers automatiquement et initialiser leurs routes)

//TODO: Gestion plus poussée des Exceptions

//TODO: Mode développement ou production (Ça modifiera principalement la gestion des Exceptions, return au lieu de throw ? avoir une class static pour les Exceptions ? utiliser $GLOBALS ?
// Quelques idées : https://stackoverflow.com/questions/834491/create-superglobal-variables-in-php)

// ---- Pas forcément utile, à voir à la fin ----
//TODO: Caching (À voir à la fin: Voir si l'utilisation que je vais en avoir nécessite un système comme celui-ci)
//TODO: Système de Captcha sur les formulaires (Pas forcément utile: un microframework n'a pas pour but d'aller aussi loin dans ses fonctionnalités)
*/
