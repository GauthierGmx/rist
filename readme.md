# RIST - Réseau social aidant l'organisation d'activités collaboratives

## Description
RIST est une application réseau social facilitant l'organisation d'activités collaboratives, appelées des "Rist". Conçue pour être utilisée par des particuliers et des professionnels, elle vise à permettre aux utilisateurs de se rencontrer et de partager des expériences ensemble, tout en respectant un budget limité.

## Objectif
L'application cible principalement :
- Les jeunes adultes souhaitant voyager et faire des rencontres
- Les personnes âgées cherchant à participer à des activités de groupe
- Toute personne cherchant à organiser ou à rejoindre des activités collaboratives à moindre coût

## Collaborateurs
- Gauthier Goumeaux
- Julien Loridant
- Axel Marrier
- Solène Martin
- Gabriel Vernis

## Arborescence des fichiers
```
RIST/
│-- Algorithme/       # Contient l'algorithme
│-- Spécification/    # Contient les spécifications externes du problème algorithmique
│-- developpement/    # Contient les pages web du projet
│-- docs/             # Contient la documentation Doxygen
│-- src/              # Contient le code source
```

## Installation
### Prérequis
- Un environnement de développement avec **Python** et **Node.js** (si nécessaire)
- Un système de gestion de base de données (MySQL, PostgreSQL, etc.)
- Git pour cloner le projet

### Cloner le projet
```sh
git clone https://github.com/nom-utilisateur/rist.git
cd rist
```

### Installation des dépendances
```sh
pip install -r requirements.txt   # Pour Python
npm install                        # Si des composants front-end sont utilisés
```

## Utilisation
1. Lancer le serveur backend :
```sh
python src/server.py
```
2. Démarrer le front-end :
```sh
npm start  # Ou une autre commande selon le framework utilisé
```
3. Accéder à l'application via [http://localhost:3000](http://localhost:3000) (ou le port défini).

## Contribuer
Les contributions sont les bienvenues ! Veuillez suivre ces étapes :
1. Fork du projet
2. Créer une branche feature : `git checkout -b ma-nouvelle-fonctionnalite`
3. Commit des modifications : `git commit -m "Ajout d'une nouvelle fonctionnalité"`
4. Push sur la branche : `git push origin ma-nouvelle-fonctionnalite`
5. Ouvrir une pull request

## Licence
Ce projet est sous licence [Nom de la licence] - voir le fichier LICENSE pour plus de détails.

## Contact
Pour toute question ou suggestion, contactez l'équipe via GitHub ou par email.

---
⚡ *Saviez-vous ?* Les réseaux sociaux collaboratifs peuvent réduire l'isolement social en facilitant les rencontres basées sur des centres d'intérêt communs !

