<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);

        $query = User::query();

        if ($request->filled('country')) {
            $query->where('country', $request->string('country'));
        }
        if ($request->filled('subscription_plan')) {
            $query->where('subscription_plan', $request->string('subscription_plan'));
        }
        if ($request->filled('is_active')) {
            $val = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($val !== null) {
                $query->where('is_active', $val);
            }
        }
        if ($request->filled('age_min')) {
            $query->where('age', '>=', (int) $request->integer('age_min'));
        }
        if ($request->filled('age_max')) {
            $query->where('age', '<=', (int) $request->integer('age_max'));
        }

        $query->orderBy('last_name')->orderBy('first_name');

        $paginator = $query->paginate($perPage)->appends($request->query());
        return UserResource::collection($paginator);
    }

    public function show(User $user)
    {
        $user->load(['reviews.movie']);
        return new UserResource($user);
    }
}


