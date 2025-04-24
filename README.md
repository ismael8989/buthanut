# Realtime Code Editor

## Cahier de charges

### Présentation du projet
**Nom du projet :** RealTime Code Editor  
**Brève description :** Une application web permettant à plusieurs utilisateurs de coder simultanément dans un même éditeur en ligne sans inscription.  
**Objectif principal de l’application :** Permettre aux utilisateurs de créer des sessions de codage collaboratif en temps réel avec exécution de code, sans avoir à créer de compte.

### Problèmes à résoudre
- Les enseignants de développement en ligne veulent aider leurs élèves en temps réel sans que chacun ait à partager son écran.
- Les candidats et recruteurs souhaitent un espace de codage interactif pour les entretiens techniques.
- Les développeurs veulent pouvoir expérimenter ensemble en temps réel, sans configurations compliquées.

### Utilisateurs cibles
- **Enseignants :** souhaitent montrer, corriger et guider les élèves sur du code en temps réel.
- **Étudiants / apprenants :** veulent apprendre de manière interactive et montrer leur raisonnement facilement.
- **Recruteurs techniques :** veulent évaluer les candidats avec des exercices de code collaboratifs.
- **Développeurs :** désirent coder ensemble rapidement pour faire des tests ou des démonstrations.

### Fonctionnalités attendues
- Créer une session de codage avec choix de l’environnement (Node.js, Python, etc.)
- Accéder à une session via un lien unique
- Éditer le code en temps réel avec d'autres personnes
- Exécuter le code dans l’environnement choisi
- Voir le résultat de l’exécution

### Contraintes techniques
- **Technologies imposées :**
  - **Backend :** Nodejs avec Express et SocketIO
  - **Frontend :** React, Monaco Editor et SocketIO client
- **Base de données prévue :** MongoDB

---

## Tableau des utilisateurs et rôles
| Type   | Description                                         | Permissions                                                                                      |
|--------|-----------------------------------------------------|--------------------------------------------------------------------------------------------------|
| guest0 | L'utilisateur qui a créé la session/room           | Peut exclure d’autres guests de la session, et aussi définir les permissions des autres invités (lecture seule, écriture). |
| guest  | L’utilisateur qui a été invité à la session/room    | Ses permissions sont définies par guest0                                                         |
