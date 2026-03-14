<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function index() {
        $users = User::all();
        return response()->json([
            'data' => UserResource::collection($users),
            'count' => $users->count(),
        ]);
    }

    public function store(CreateUserRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'telephone' => $request->telephone,
            'role' => $request->role,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(User $user) {
        return response()->json(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user) {
        $data = $request->validated();
        
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user) {
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé',
        ]);
    }

    public function getByRole($role) {
        $users = User::where('role', $role)->get();
        return response()->json([
            'data' => UserResource::collection($users),
            'count' => $users->count(),
        ]);
    }
}
