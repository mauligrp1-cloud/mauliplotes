<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    /**
     * Display a listing of users & roles
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleSlug = $request->input('role')) {
            $query->whereHas('roles', function ($q) use ($roleSlug) {
                $q->where('slug', $roleSlug);
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::withCount('users')->get();
        $totalUsers = User::count();

        return view('admin.users.index', compact('users', 'roles', 'totalUsers'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|max:72',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        // Anti-privilege-escalation: only Super Admins can assign super-admin or admin roles
        if (!auth()->user()->isSuperAdmin()) {
            $privilegedRoleIds = Role::whereIn('slug', ['super-admin', 'admin'])->pluck('id')->toArray();
            foreach ($validated['roles'] as $roleId) {
                if (in_array($roleId, $privilegedRoleIds)) {
                    \Illuminate\Support\Facades\Log::warning('Security: Unauthorized privilege escalation attempt in user store.', [
                        'by_user' => auth()->id(),
                        'attempted_role_id' => $roleId
                    ]);
                    abort(403, 'Only a Super Administrator can assign administrative roles.');
                }
            }
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        \Illuminate\Support\Facades\Log::info('Security: User created by admin.', [
            'created_user_id' => $user->id,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created successfully with assigned role(s).");
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only a Super Administrator can edit a Super Administrator account.');
        }

        $roles = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only a Super Administrator can edit a Super Administrator account.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|max:72',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        // Anti-privilege-escalation: only Super Admins can assign super-admin or admin roles
        if (!auth()->user()->isSuperAdmin()) {
            $privilegedRoleIds = Role::whereIn('slug', ['super-admin', 'admin'])->pluck('id')->toArray();
            foreach ($validated['roles'] as $roleId) {
                if (in_array($roleId, $privilegedRoleIds)) {
                    \Illuminate\Support\Facades\Log::warning('Security: Unauthorized privilege escalation attempt in user update.', [
                        'by_user' => auth()->id(),
                        'target_user' => $user->id,
                        'attempted_role_id' => $roleId
                    ]);
                    abort(403, 'Only a Super Administrator can assign administrative roles.');
                }
            }
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->roles()->sync($validated['roles']);

        \Illuminate\Support\Facades\Log::info('Security: User updated by admin.', [
            'target_user_id' => $user->id,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own active administrator account.');
        }

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only a Super Administrator can delete a Super Administrator account.');
        }

        if ($user->isSuperAdmin()) {
            $superAdminCount = User::whereHas('roles', function ($q) {
                $q->where('slug', 'super-admin');
            })->count();

            if ($superAdminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Cannot delete the only Super Admin account on the system.');
            }
        }

        $name = $user->name;
        $user->roles()->detach();
        $user->delete();

        \Illuminate\Support\Facades\Log::info('Security: User deleted by admin.', [
            'deleted_user_name' => $name,
            'deleted_by' => auth()->id()
        ]);

        return redirect()->route('admin.users.index')->with('success', "User '{$name}' has been deleted successfully.");
    }
}
