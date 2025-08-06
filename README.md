# Projet Pluvio 2025 - Dylan
## Présentation générale
Le projet « PLUVIO » a pour objectif de s’intégrer à un dispositif de récupération d’eau pluviale afin d’en améliorer le suivi et la gestion. Il permet de collecter des données précises concernant l’environnement du récupérateur
(comme les précipitations ou la température), son état de fonctionnement (niveau d’eau, éventuelles anomalies) ainsi que son usage (consommation, remplissage).  
Ces données sont ensuite transmises via un réseau de communication (comme LoRa), puis archivées et rendues accessibles à distance. Enfin, elles peuvent être analysées pour optimiser l’utilisation de l’eau, anticiper les besoins ou
encore détecter d’éventuels dysfonctionnements.

## Analyse de l’existant

### Partenariat
Le dispositif a été développé dans le cadre d’un projet collaboratif réunissant plusieurs partenaires institutionnels, industriels et pédagogiques. Ce travail commun a permis la mise en place d’un système complet de récupération et
de gestion des eaux pluviales, grâce à la participation de diverses sections de l’établissement scolaire et d’acteurs extérieurs. Les partenaires impliqués sont :
- L’OGEC Hazparne
- La tannerie CARRIAT (Espelette), qui a fourni quatre cuves de 1000 litres
- La section TRPM, chargée de la fabrication du collecteur
- La section MEE, responsable du réseau de canalisation et de l’armoire électrique pour les électrovannes
- La section MELEC, en charge du câblage
- Les sections CIEL et MELEC, pour la programmation de la commande de la pompe
- Le projet Erasmus, avec la participation d’étudiants italiens

### Objectif de l’OGEC Hazparne
L’ensemble scolaire OGEC Hazparne a choisi d’installer un dispositif de récupération d’eau pluviale pour l’usage des sanitaires en partant du constat que les chasses d'eau représentent une part significative de la consommation d'eau potable.
En effet :
- 3 à 6 litres d'eau potable sont utilisés à chaque chasse d'eau
- Jusqu'à 10 litres pour les anciennes chasses d'eau
- 20% à 40% de la consommation d'eau domestique est dédiée aux toilettes

## Ma partie
Dans ce projet, je dois réaliser l’IHM (Interface Homme Machine) ou site Web qui permettra au responsable du site, aux administrateurs ou aux utilisateurs d'accéder aux relevés pluviométriques.  
L'API REST fonctionne principalement en méthode POST pour recevoir les données depuis les dispositifs connectés.
Le site Web permet un accès en consultation pour les utilisateurs standards authentifiés et en administration pour les personnes ayant ce profil :
- Utilisateur : consulter les niveaux de cuves, la pluviométrie par heure et les historiques de mesures.
- Administrateur (gérant ou technicien) : administrer (CRUD) les utilisateurs et les mesures.  

En parallèle, la partie physique, que je développe, comprend le paramétrage du WiFi et la configuration réseau nécessaire pour assurer la bonne transmission et réception des données pluviométriques vers le backend Symfony via une API REST.

## Technologies utilisées

- Symfony 6.4
- API Platform 4.1
- UX Chart.js
- WebPack Encore
- Doctrine ORM
- MySQL
- PHP 8.4
- Postman / cURL pour les tests
