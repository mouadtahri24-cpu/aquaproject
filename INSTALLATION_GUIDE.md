# Guide Complet d'Installation - Club Natation

## 📋 Vue d'ensemble

Ce guide vous aide à intégrer tous les fichiers PHP dans votre projet Laravel pour la gestion du Club de Natation.

## ✅ Fichiers Créés

### 1. **Modèles (Models)** - `app/Models/`
```
✓ Swimmer.php
✓ Group.php
✓ Session.php
✓ Attendance.php
✓ Event.php
✓ Performance.php
✓ Payment.php
✓ Announcement.php
✓ Conversation.php
✓ Message.php
✓ Notification.php
```

### 2. **Contrôleurs (Controllers)** - `app/Http/Controllers/Api/`
```
✓ AuthController.php         (déjà créé)
✓ UserController.php
✓ SwimmerController.php
✓ GroupController.php
✓ SessionController.php
✓ AttendanceController.php
✓ PerformanceController.php
✓ PaymentController.php
✓ AnnouncementController.php
✓ MessageController.php
✓ NotificationController.php
```

### 3. **Migrations** - `database/migrations/`
```
✓ 2025_create_swimmers_table.php
✓ 2025_create_groups_table.php
✓ 2025_create_sessions_table.php
✓ 2025_create_attendances_table.php
✓ 2025_create_events_table.php
✓ 2025_create_performances_table.php
✓ 2025_create_payments_table.php
✓ 2025_create_announcements_table.php
✓ 2025_create_conversations_table.php
✓ 2025_create_messages_table.php
✓ 2025_create_notifications_table.php
```

### 4. **Middlewares** - `app/Http/Middleware/`
```
✓ CheckRole.php
```

### 5. **Routes** - `routes/`
```
✓ api.php (remplacer ou fusionner avec votre fichier existant)
```

---

## 🚀 Étapes d'Installation

### Étape 1 : Copier les Modèles
```bash
# Copiez les 11 fichiers modèles dans app/Models/
cp Swimmer.php app/Models/
cp Group.php app/Models/
cp Session.php app/Models/
# ... etc pour tous les autres modèles
```

### Étape 2 : Copier les Contrôleurs
```bash
# Créez le dossier Api s'il n'existe pas
mkdir -p app/Http/Controllers/Api

# Copiez tous les contrôleurs
cp UserController.php app/Http/Controllers/Api/
cp SwimmerController.php app/Http/Controllers/Api/
# ... etc pour tous les autres contrôleurs
```

### Étape 3 : Copier les Migrations
```bash
# Copiez les migrations avec le bon format de date
# NOTE : Laravel utilise un format datetime pour les migrations
# Utilisez: YYYY_MM_DD_HHMMSS_create_tablename_table.php

cp 2025_create_swimmers_table.php database/migrations/
cp 2025_create_groups_table.php database/migrations/
# ... etc pour toutes les migrations
```

**IMPORTANT** : Renommez les migrations avec la date/heure actuelle :
```bash
# Exemple :
# 2025_01_15_120000_create_swimmers_table.php
```

### Étape 4 : Copier le Middleware
```bash
cp CheckRole.php app/Http/Middleware/
```

### Étape 5 : Mettre à Jour les Routes
```bash
# Remplacez ou fusionnez routes/api.php avec le fichier api.php fourni
cp api.php routes/api.php
```

### Étape 6 : Enregistrer le Middleware
Modifiez `app/Http/Middleware/Kernel.php` et ajoutez :
```php
protected $routeMiddleware = [
    // ... autres middlewares
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

---

## 🔄 Exécuter les Migrations

```bash
# Créer les tables dans la base de données
php artisan migrate

# Si vous avez des erreurs, rollback et réessayez
php artisan migrate:rollback
```

---

## 🔐 Configuration Sanctum

Vérifiez que Laravel Sanctum est installé :
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Modifiez `config/sanctum.php` si nécessaire pour configurer l'expiration des tokens.

---

## 📝 Requêtes de Test (Postman/cURL)

### 1. **Login**
```bash
POST /api/v1/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Réponse :**
```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": "admin"
  },
  "token": "1|abc...xyz",
  "token_type": "Bearer"
}
```

### 2. **Créer un Utilisateur (Admin)**
```bash
POST /api/v1/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Coach Jean",
  "email": "coach.jean@example.com",
  "password": "password123",
  "telephone": "+212612345678",
  "role": "coach",
  "is_active": true
}
```

### 3. **Créer un Nageur**
```bash
POST /api/v1/swimmers
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "Ahmed",
  "last_name": "Benasser",
  "birth_date": "2010-05-15",
  "parent_id": 2,
  "group_id": 1,
  "status": "active"
}
```

### 4. **Créer une Séance (Admin)**
```bash
POST /api/v1/sessions
Authorization: Bearer {token}
Content-Type: application/json

{
  "group_id": 1,
  "coach_id": 3,
  "session_date": "2025-03-15",
  "start_time": "10:00",
  "end_time": "11:30",
  "type": "Entrainement",
  "objective": "Améliorer le crawl"
}
```

### 5. **Enregistrer une Présence (Coach)**
```bash
POST /api/v1/attendances
Authorization: Bearer {token}
Content-Type: application/json

{
  "session_id": 1,
  "swimmer_id": 1,
  "status": "Present",
  "reason": null
}
```

### 6. **Enregistrer une Performance (Coach)**
```bash
POST /api/v1/performances
Authorization: Bearer {token}
Content-Type: application/json

{
  "swimmer_id": 1,
  "event_id": 1,
  "session_id": 1,
  "performance_date": "2025-03-15",
  "time_seconds": 45.50,
  "notes": "Bon chrono"
}
```

### 7. **Enregistrer un Paiement (Admin)**
```bash
POST /api/v1/payments
Authorization: Bearer {token}
Content-Type: application/json

{
  "swimmer_id": 1,
  "month": "2025-03",
  "amount_expected": 500.00,
  "amount_paid": 500.00,
  "status": "Paid",
  "paid_at": "2025-03-01T10:00:00Z"
}
```

---

## 🛡️ Sécurité & Permissions

### Matrice RBAC Appliquée :

| Module | Admin | Coach | Parent |
|--------|-------|-------|--------|
| Utilisateurs | CRUD | — | — |
| Nageurs | CRUD | Lecture (groupe) | Lecture (enfants) |
| Groupes | CRUD | Lecture | — |
| Planning | CRUD | Lecture | Lecture |
| Présences | Lecture | Créer/Modifier | Lecture |
| Performances | Lecture | Créer/Modifier | Lecture |
| Paiements | CRUD | — | Lecture |
| Messagerie | Tous | Tous sauf Parent-Parent | Tous sauf Parent-Parent |

---

## 🔧 Dépannage

### Erreur : "Class not found"
**Solution :** Assurez-vous que tous les fichiers sont dans les bonnes structures de dossiers.

```bash
php artisan dump-autoload
```

### Erreur : "Table not found"
**Solution :** Exécutez les migrations :
```bash
php artisan migrate
```

### Erreur : "SQLSTATE[42S22]: Column not found"
**Solution :** Vérifiez que toutes les migrations ont été exécutées dans le bon ordre.

```bash
php artisan migrate:status
```

---

## 📚 Prochaines Étapes

1. **Créer des Seeders** pour insérer des données de test
2. **Ajouter les validations supplémentaires** (format e-mail, téléphone, etc.)
3. **Implémenter des Tests Unitaires** avec PHPUnit
4. **Configurer la documentation API** avec Swagger/OpenAPI
5. **Ajouter les Logs d'Audit** pour les actions sensibles
6. **Implémenter la Pagination** pour les listes volumineuses

---

## 📞 Support

Pour toute question ou erreur, vérifiez :
- Les logs : `storage/logs/laravel.log`
- La base de données : Vérifiez que toutes les tables sont créées
- Les permissions : Vérifiez les rôles de l'utilisateur authentifié

---

**Projet de Fin d'Études - Club de Natation**  
Stack : Laravel 11 + React.js + MySQL  
Année : 2025-2026
