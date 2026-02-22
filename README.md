🌐 Application Web de Gestion Scolaire (CSBJS)

Ce projet est une solution complète de gestion scolaire développée pour le Complexe Scolaire Baptiste Jésus Sauve. Elle permet de centraliser la gestion pédagogique et d'assurer un suivi transparent entre l'administration, les enseignants et les parents.
✅ Fonctionnalités
🛠️ Espace Administrateur

    Tableau de bord : Vue d'ensemble de l'établissement.

    Gestion des ressources : Contrôle total sur les classes, matières, élèves et professeurs.

    Affectations : Liaison dynamique des professeurs aux classes et matières.

    Communication : Système d'envoi d'emails groupés aux parents.

    Supervision : Consultation des messages envoyés par le corps enseignant.

    Gestion du Site Vitrine : Publication et modération des actualités de l'école.

👨‍🏫 Espace Professeur

    Gestion des Notes : Saisie sécurisée des notes par trimestre.

    Suivi Pédagogique : Envoi de messages personnalisés aux parents pour le suivi individuel.

🔐 Sécurité & Performance

    Protection des accès : Utilisation de sessions PHP et de middlewares (auth_admin.php, auth_prof.php).

    Données Sensibles : Mots de passe hashés avec password_hash().

    Interface Adaptive : Design responsive (Mobile First) pour une consultation sur smartphone et tablette.

🛠️ Technologies utilisées

    Backend : PHP 8.x (Architecture modulaire)

    Base de données : MySQL

    Frontend : HTML5, CSS3 (Google Fonts Poppins)

    Bibliothèques : PHPMailer (Emails), intl-tel-input (Validation téléphone),TCPDF
   

    Environnement : WAMP / Serveur Linux (LAMP)

📁 Structure du projet
🚀 Installation locale

    Cloner le projet : git clone https://github.com/ton-pseudo/nom-du-repo.git

    Base de données : Importer le fichier .sql (disponible dans /sql/) dans votre gestionnaire MySQL.

    Configuration : Modifier includes/db.php avec vos accès locaux.

    Lancement : Placer le dossier dans www/ (WAMP) ou htdocs/ (XAMPP).

📅 Évolutions futures

    [ ] Module de gestion des absences (Feuilles d'appel numériques).

    [ ] Génération automatique de bulletins au format PDF.

    [ ] Espace parent dédié pour la consultation des notes en temps réel.

👨‍💻 Auteur

Amen ATTIOGBE - Etudiant en Licence 2 Informatique

    Email : elomattiogbe05@gmail.com

    Projet : Déploiement professionnel pour école locale.
