<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole {
    public function handle(Request $request, Closure $next, ...$roles): Response {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Non authentifié',
            ], 401);
        }

        $user = auth()->user();

        // Vérifier si le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Non autorisé. Vous n\'avez pas les permissions nécessaires.',
                'required_roles' => $roles,
                'your_role' => $user->role,
            ], 403);
        }

        return $next($request);
    }
}
