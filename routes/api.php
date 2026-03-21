<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SwimmerController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\EventController;

Route::prefix('v1')->group(function () {
    
    // ===================== AUTH (Publique) =====================
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']); // Inscription publique

    // ===================== ROUTES PROTÉGÉES =====================
    Route::middleware('auth:sanctum')->group(function () {
        
        // ===== AUTH =====
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // ===== USERS (Admin uniquement) =====
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::get('/users/role/{role}', [UserController::class, 'getByRole']);
            Route::get('/users/pending', [UserController::class, 'getPending']); // Voir comptes en attente
            Route::post('/users/{user}/approve', [UserController::class, 'approve']); // Valider un compte
            Route::post('/users/{user}/reject', [UserController::class, 'reject']); // Rejeter un compte
        });

        // ===== SWIMMERS =====
        Route::prefix('swimmers')->group(function () {
            // Admin et Coach peuvent lire
            Route::get('/', [SwimmerController::class, 'index'])->middleware('role:admin,coach');
            Route::get('/{swimmer}', [SwimmerController::class, 'show'])->middleware('role:admin,coach');
            
            // Parent peut voir ses enfants
            Route::get('/parent/{parentId}', [SwimmerController::class, 'getByParent'])->middleware('role:parent');
            
            // Coach peut voir son groupe
            Route::get('/group/{groupId}', [SwimmerController::class, 'getByGroup'])->middleware('role:coach');
            
            // Admin crée/modifie/supprime
            Route::post('/', [SwimmerController::class, 'store'])->middleware('role:admin');
            Route::put('/{swimmer}', [SwimmerController::class, 'update'])->middleware('role:admin');
            Route::delete('/{swimmer}', [SwimmerController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== GROUPS =====
        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->middleware('role:admin,coach');
            Route::get('/{group}', [GroupController::class, 'show'])->middleware('role:admin,coach');
            Route::get('/coach/{coachId}', [GroupController::class, 'getByCoach'])->middleware('role:coach');
            Route::get('/category/{category}', [GroupController::class, 'getByCategory']);
            
            Route::post('/', [GroupController::class, 'store'])->middleware('role:admin');
            Route::put('/{group}', [GroupController::class, 'update'])->middleware('role:admin');
            Route::delete('/{group}', [GroupController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== SESSIONS (Séances) =====
        Route::prefix('sessions')->group(function () {
            Route::get('/', [SessionController::class, 'index'])->middleware('role:admin,coach,parent');
            Route::get('/upcoming', [SessionController::class, 'getUpcoming'])->middleware('role:admin,coach,parent');
            Route::get('/group/{groupId}', [SessionController::class, 'getByGroup'])->middleware('role:coach,parent');
            Route::get('/coach/{coachId}', [SessionController::class, 'getByCoach'])->middleware('role:coach');
            Route::get('/{session}', [SessionController::class, 'show'])->middleware('role:admin,coach,parent');
            
            Route::post('/', [SessionController::class, 'store'])->middleware('role:admin');
            Route::put('/{session}', [SessionController::class, 'update'])->middleware('role:admin');
            Route::delete('/{session}', [SessionController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== ATTENDANCES (Présences) =====
        Route::prefix('attendances')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->middleware('role:admin,coach');
            Route::get('/session/{sessionId}', [AttendanceController::class, 'getBySession'])->middleware('role:admin,coach');
            Route::get('/swimmer/{swimmerId}', [AttendanceController::class, 'getBySwimmer'])->middleware('role:admin,coach,parent');
            Route::get('/{attendance}', [AttendanceController::class, 'show'])->middleware('role:admin,coach');
            
            Route::post('/', [AttendanceController::class, 'store'])->middleware('role:admin,coach');
            Route::put('/{attendance}', [AttendanceController::class, 'update'])->middleware('role:admin,coach');
            Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== PERFORMANCES =====
        Route::prefix('performances')->group(function () {
            Route::get('/', [PerformanceController::class, 'index'])->middleware('role:admin,coach,parent');
            Route::get('/swimmer/{swimmerId}', [PerformanceController::class, 'getBySwimmer'])->middleware('role:admin,coach,parent');
            Route::get('/swimmer/{swimmerId}/records', [PerformanceController::class, 'getPersonalRecords'])->middleware('role:admin,coach,parent');
            Route::get('/swimmer/{swimmerId}/event/{eventId}/best', [PerformanceController::class, 'getBestByEvent'])->middleware('role:admin,coach,parent');
            Route::get('/{performance}', [PerformanceController::class, 'show'])->middleware('role:admin,coach,parent');
            
            Route::post('/', [PerformanceController::class, 'store'])->middleware('role:admin,coach');
            Route::put('/{performance}', [PerformanceController::class, 'update'])->middleware('role:admin,coach');
            Route::delete('/{performance}', [PerformanceController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== PAYMENTS (Paiements) =====
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->middleware('role:admin');
            Route::get('/late', [PaymentController::class, 'getLate'])->middleware('role:admin');
            Route::get('/swimmer/{swimmerId}', [PaymentController::class, 'getBySwimmer'])->middleware('role:admin,parent');
            Route::get('/{payment}', [PaymentController::class, 'show'])->middleware('role:admin,parent');
            
            Route::post('/', [PaymentController::class, 'store'])->middleware('role:admin');
            Route::put('/{payment}', [PaymentController::class, 'update'])->middleware('role:admin');
            Route::post('/{payment}/record', [PaymentController::class, 'recordPayment'])->middleware('role:admin');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== ANNOUNCEMENTS (Annonces) =====
        Route::prefix('announcements')->group(function () {
            Route::get('/', [AnnouncementController::class, 'getPublished']);
            Route::get('/type/{type}', [AnnouncementController::class, 'getByType']);
            Route::get('/{announcement}', [AnnouncementController::class, 'show']);
            
            Route::post('/', [AnnouncementController::class, 'store'])->middleware('role:admin,coach');
            Route::post('/{announcement}/notify', [AnnouncementController::class, 'notifyAll'])->middleware('role:admin');
            Route::put('/{announcement}', [AnnouncementController::class, 'update'])->middleware('role:admin,coach');
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('role:admin');
        });

        // ===== CONVERSATIONS (Messagerie) =====
        Route::prefix('conversations')->group(function () {
            Route::get('/', [ConversationController::class, 'index']);
            Route::get('/{conversation}', [ConversationController::class, 'show']);
            Route::get('/{conversation}/mark-read', [ConversationController::class, 'markAsRead']);
            
            Route::post('/', [ConversationController::class, 'store']);
            Route::delete('/{conversation}', [ConversationController::class, 'destroy']);
        });

        // ===== MESSAGES =====
        Route::prefix('messages')->group(function () {
            Route::get('/', [MessageController::class, 'index']);
            Route::get('/unread', [MessageController::class, 'getUnread']);
            Route::get('/{message}', [MessageController::class, 'show']);
            Route::post('/{message}/mark-read', [MessageController::class, 'markAsRead']);
            
            Route::post('/', [MessageController::class, 'store']);
            Route::delete('/{message}', [MessageController::class, 'destroy']);
        });

        // ===== NOTIFICATIONS =====
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/unread', [NotificationController::class, 'getUnread']);
            Route::get('/count/unread', [NotificationController::class, 'countUnread']);
            Route::get('/{notification}', [NotificationController::class, 'show']);
            
            Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
            Route::post('/mark-all/read', [NotificationController::class, 'markAllAsRead']);
            Route::delete('/{notification}', [NotificationController::class, 'destroy']);
            Route::delete('/', [NotificationController::class, 'destroyAll']);
        });

        // ===== EVENTS (Épreuves) =====
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index']);
            Route::get('/{event}', [EventController::class, 'show']);
            Route::get('/stroke/{stroke}', [EventController::class, 'getByStroke']);
            
            Route::post('/', [EventController::class, 'store'])->middleware('role:admin');
            Route::put('/{event}', [EventController::class, 'update'])->middleware('role:admin');
            Route::delete('/{event}', [EventController::class, 'destroy'])->middleware('role:admin');
        });
    });
});
