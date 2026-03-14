<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwimmerRequest;
use App\Http\Requests\UpdateSwimmerRequest;
use App\Http\Resources\SwimmerResource;
use App\Models\Swimmer;
use Illuminate\Http\Request;

class SwimmerController extends Controller {
    public function index() {
        $swimmers = Swimmer::with(['parent', 'group'])->get();
        return response()->json([
            'data' => SwimmerResource::collection($swimmers),
            'count' => $swimmers->count(),
        ]);
    }

    public function store(StoreSwimmerRequest $request) {
        $swimmer = Swimmer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'parent_id' => $request->parent_id,
            'group_id' => $request->group_id,
            'level' => $request->level ?? 'Débutant',
            'status' => $request->status ?? 'active',
            'is_minor' => true,
        ]);

        return response()->json([
            'message' => 'Nageur créé avec succès',
            'data' => new SwimmerResource($swimmer->load(['parent', 'group'])),
        ], 201);
    }

    public function show(Swimmer $swimmer) {
        return response()->json(new SwimmerResource($swimmer->load(['parent', 'group'])));
    }

    public function update(UpdateSwimmerRequest $request, Swimmer $swimmer) {
        $swimmer->update($request->validated());

        return response()->json([
            'message' => 'Nageur mis à jour',
            'data' => new SwimmerResource($swimmer->load(['parent', 'group'])),
        ]);
    }

    public function destroy(Swimmer $swimmer) {
        $swimmer->delete();

        return response()->json([
            'message' => 'Nageur supprimé',
        ]);
    }

    public function getByGroup($groupId) {
        $swimmers = Swimmer::where('group_id', $groupId)->with(['parent', 'group'])->get();
        return response()->json([
            'data' => SwimmerResource::collection($swimmers),
            'count' => $swimmers->count(),
        ]);
    }

    public function getByParent($parentId) {
        $swimmers = Swimmer::where('parent_id', $parentId)->with(['parent', 'group'])->get();
        return response()->json([
            'data' => SwimmerResource::collection($swimmers),
            'count' => $swimmers->count(),
        ]);
    }
}
