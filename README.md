# Buthanut – Gestion de crédit pour petits commerces

## Cahier des charges

### Présentation du projet
**Nom du projet :** Buthanut  
**Brève description :** Une application web permettant aux propriétaires de petits commerces (buthanut) de suivre les crédits accordés à leurs clients, de manière simple, digitale et sécurisée. Les clients peuvent aussi consulter leurs dettes sans pouvoir les modifier.  
**Objectif principal de l’application :** Digitaliser le système traditionnel des carnets physiques utilisés par les épiciers pour suivre les dettes de leurs clients.

### Contexte
Au Maroc, il est courant que les propriétaires de petites boutiques vendent des produits à crédit et notent les montants dus dans des carnets papier. Chaque client a souvent son propre petit carnet. En fin de mois, le commerçant calcule le total et le client paie. Ce système peut mener à des pertes d’informations, des erreurs de calcul ou des désaccords.

### Problèmes à résoudre
- Risque de perte ou d’endommagement des carnets physiques
- Difficulté à faire des calculs mensuels rapidement
- Manque de transparence pour les clients
- Aucun historique exploitable à long terme

### Utilisateurs cibles
- **buthanut (propriétaires de boutique) :** veulent gérer leurs crédits efficacement, sans perdre de temps ni d’information.
- **Clients :** souhaitent consulter à tout moment le montant total de leurs dettes, sans avoir à se déplacer ou demander au commerçant.

### Fonctionnalités attendues
- Création de comptes pour chaque buthanut et client
- Interface simple pour ajouter/modifier/supprimer des transactions pour chaque client
- Calcul automatique du total dû par client
- Historique des transactions
- Accès client en lecture seule
- Sécurisation des données

### Contraintes techniques
- **Backend :** Laravel
- **Frontend :** React
- **Base de données :** MySQL
- **Containerisation :** Docker
---

## Tableau des utilisateurs et rôles

| Type        | Description                                            | Permissions                                                                 |
|-------------|--------------------------------------------------------|------------------------------------------------------------------------------|
| **Buthanout** | Propriétaire de la boutique                          | Peut créer/modifier/supprimer des clients et leurs transactions             |
| **Client**   | Personne ayant un crédit auprès d’un buthanout        | Peut consulter ses dettes et son historique, mais ne peut rien modifier     |
