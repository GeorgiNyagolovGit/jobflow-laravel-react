<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($users) use ($search) {
                $users->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'users' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('view', $user);

        return response()->json([
            'user' => $user->load('roles'),
            'roles' => Role::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
            'roles' => [
                'sometimes',
                'array',
            ],
            'roles.*' => [
                'integer',
                'distinct',
                'exists:roles,id',
            ],
        ]);

        $roles = isset($data['roles']) ? $data['roles'] : null;

        if ($roles !== null) {
            $this->authorize('assignRoles', $user);
        }

        unset($data['roles']);

        $user->update($data);

        if ($roles !== null) {
            $user->roles()->sync($roles);
        }

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user->fresh()->load('roles'),
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
