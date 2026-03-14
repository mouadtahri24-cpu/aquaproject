<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller {
    public function index() {
        $groups = Group::with('coach')->get();
        return response()->json([
            'data' => GroupResource::collection($groups),
            'count' => $groups->count(),
        ]);
    }

    public function store(StoreGroupRequest $request) {
        $group = Group::create([
            'name' => $request->name,
            'level' => $request->level,
            'schedule_label' => $request->schedule_label,
            'coach_id' => $request->coach_id,
            'age_category' => $request->age_category,
            'min_age' => match($request->age_category) {
                'benjamin' => 6,
                'cadet' => 10,
                'junior' => 13,
            },
            'max_age' => match($request->age_category) {
                'benjamin' => 9,
                'cadet' => 12,
                'junior' => 17,
            },
            'monthly_fee' => $request->monthly_fee,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'message' => 'Groupe créé avec succès',
            'data' => new GroupResource($group->load('coach')),
        ], 201);
    }

    public function show(Group $group) {
        return response()->json(new GroupResource($group->load('coach')));
    }

    public function update(UpdateGroupRequest $request, Group $group) {
        $data = $request->validated();

        if ($request->has('age_category')) {
            $data['min_age'] = match($request->age_category) {
                'benjamin' => 6,
                'cadet' => 10,
                'junior' => 13,
            };
            $data['max_age'] = match($request->age_category) {
                'benjamin' => 9,
                'cadet' => 12,
                'junior' => 17,
            };
        }

        $group->update($data);

        return response()->json([
            'message' => 'Groupe mis à jour',
            'data' => new GroupResource($group->load('coach')),
        ]);
    }

    public function destroy(Group $group) {
        $group->delete();

        return response()->json([
            'message' => 'Groupe supprimé',
        ]);
    }

    public function getByCoach($coachId) {
        $groups = Group::where('coach_id', $coachId)->with('coach')->get();
        return response()->json([
            'data' => GroupResource::collection($groups),
            'count' => $groups->count(),
        ]);
    }

    public function getByCategory($category) {
        $groups = Group::where('age_category', $category)->with('coach')->get();
        return response()->json([
            'data' => GroupResource::collection($groups),
            'count' => $groups->count(),
        ]);
    }
}
